import { apiPost } from "./client";

export type LoginResponse = {
  token: string; 
  user?: any;
};

export async function login(email: string, password: string) {
  return apiPost<LoginResponse>("/api/login", { email, password });
}
