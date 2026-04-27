import React, { createContext, useContext, useEffect, useState } from "react";
import { apiPostAuth } from "../api/client";
import { me as meApi, type MeUser } from "../api/me";
import { clearToken, getToken, setToken } from "./tokenStore";

type User = MeUser;
//lista co dostarczamy 
type AuthState = {
  user: User | null;//sprawdzam czy user to obiekt user lub null, zalogowany albo nie
  isLoading: boolean;//app jest w trakcie sprawdzani stanu log
  signIn: (token: string) => Promise<void>;//tu bedzie funkcja logowania 
  signOut: () => Promise<void>;//wylogowania 
  refreshMe: () => Promise<void>;//odswiez
  totalPoints: number;
level: number;
};
//przechowujemy w authcontext wszystko
const AuthContext = createContext<AuthState | null>(null);
//implementacja
export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);//user zmieniamy tylko przez set user
  const [isLoading, setIsLoading] = useState(true);
  const [totalPoints, setTotalPoints] = useState(0);
  const [level, setLevel] = useState(0);

  //
  async function refreshMe() {
    const res = await meApi(); 
    setUser(res.user);
    setTotalPoints(res.totalPoints);
    setLevel(res.level);
  }

  async function signIn(token: string) {
    await setToken(token);
    await refreshMe();
  }

  async function signOut() {
    try {
      // opcjonalnie: jeśli masz endpoint logout w API
      await apiPostAuth("/api/logout", {});
    } catch {
      // ignorujemy - i tak czyścimy token lokalnie
    }
    await clearToken();
    setUser(null);
  }

  useEffect(() => {
  (async () => {
    try {
      const token = await getToken();
      if (token) {
        try {
          await refreshMe();
        } catch (e) {
          // token jest, ale backend mówi unauth -> czyścimy token
          await clearToken();
          setUser(null);
        }
      }
    } finally {
      setIsLoading(false);
    }
  })();
}, []);

  return (
    <AuthContext.Provider value={{ user, isLoading, signIn, signOut, refreshMe,totalPoints,level }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
}
