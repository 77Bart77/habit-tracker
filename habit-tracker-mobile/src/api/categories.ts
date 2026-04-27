import { apiGetAuth } from "./client";

export type GoalCategory = {
  id: number;
  name?: string;
  title?: string;
};

export async function getGoalCategories(): Promise<GoalCategory[]> {
  const res = await apiGetAuth<any>("/api/goal-categories");

  console.log("RAW /api/goal-categories response:", res);

  if (Array.isArray(res)) return res;
  if (Array.isArray(res?.data)) return res.data;
  if (Array.isArray(res?.categories)) return res.categories;
  if (Array.isArray(res?.goal_categories)) return res.goal_categories;

  return [];
}
