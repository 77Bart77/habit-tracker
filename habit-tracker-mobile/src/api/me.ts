import { apiGetAuth } from "./client";

export type MeUser = { id: number; name: string; email: string };
export type MeResponse = { user: MeUser, totalPoints: number, level: number };

export async function me(): Promise<MeResponse> {
  return apiGetAuth<MeResponse>("/api/me");

  
}
