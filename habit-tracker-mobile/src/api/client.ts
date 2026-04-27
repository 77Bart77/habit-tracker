import { clearToken, getToken } from "../auth/tokenStore";
import { API_BASE_URL } from "./config";

type ApiError = Error & { status?: number; payload?: any };

function makeApiError(message: string, status?: number, payload?: any): ApiError {
  const err = new Error(message) as ApiError;
  err.status = status;
  err.payload = payload;
  return err;
}

async function request<T>(path: string, options: RequestInit): Promise<T> {
  const res = await fetch(`${API_BASE_URL}${path}`, {
    ...options,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...(options.headers || {}),
    },
  });

  // czytamy odpowiedź jako tekst (bo czasem backend zwraca pusty body lub nie-JSON)
  const text = await res.text();

  // próbujemy sparsować JSON, ale bez wywalania całej aplikacji
  let data: any = null;
  if (text) {
    try {
      data = JSON.parse(text);
    } catch {
      data = null; // np. HTML z błędem / tekst
    }
  }

  if (!res.ok) {
    // jeśli token nieważny → wyczyść token lokalnie
    if (res.status === 401) {
      await clearToken();
    }

    const msg =
      (data && (data.message || data.error)) ||
      (text && text.slice(0, 120)) ||
      `HTTP ${res.status}`;

    throw makeApiError(msg, res.status, data);
  }

  return data as T;
}

export const apiGet = <T>(path: string) => request<T>(path, { method: "GET" });

export const apiPost = <T>(path: string, body?: unknown) =>
  request<T>(path, {
    method: "POST",
    body: body === undefined ? undefined : JSON.stringify(body),
  });

/** GET z Authorization Bearer */
export async function apiGetAuth<T>(path: string) {
  const token = await getToken();
  if (!token) throw makeApiError("Brak tokenu (niezalogowany).", 401);

  return request<T>(path, {
    method: "GET",
    headers: { Authorization: `Bearer ${token}` },
  });
}

/** POST z Authorization Bearer */
export async function apiPostAuth<T>(path: string, body?: unknown) {
  const token = await getToken();
  if (!token) throw makeApiError("Brak tokenu (niezalogowany).", 401);

  return request<T>(path, {
    method: "POST",
    body: body === undefined ? undefined : JSON.stringify(body),
    headers: { Authorization: `Bearer ${token}` },
  });
}

/** PATCH z Authorization Bearer */
export async function apiPatchAuth<T>(path: string, body?: unknown) {
  const token = await getToken();
  if (!token) throw makeApiError("Brak tokenu (niezalogowany).", 401);

  return request<T>(path, {
    method: "PATCH",
    body: body === undefined ? undefined : JSON.stringify(body),
    headers: { Authorization: `Bearer ${token}` },
  });
}

/** DELETE z Authorization Bearer */
export async function apiDeleteAuth<T>(path: string) {
  const token = await getToken();
  if (!token) throw makeApiError("Brak tokenu (niezalogowany).", 401);

  return request<T>(path, {
    method: "DELETE",
    headers: { Authorization: `Bearer ${token}` },
  });
}