import { apiDeleteAuth, apiGetAuth, apiPatchAuth, apiPostAuth } from "./client";


export type GoalStatus = "active" | "finished" | "paused";
export type GoalDayStatus = "pending" | "done" | "skipped";

// lista celów typ obiektu
export type Goal = {
  id: number;
  title: string;
  status?: GoalStatus;
  start_date?: string;
  end_date?: string;
  is_public?: boolean;
};

//typ obiektów , ma tablice goal
export type GoalsResponse = {
  goals: Goal[];
};
//pobieramy cele
export async function getGoals() {
  return apiGetAuth<GoalsResponse>(`/api/goals`);
}

// szczegóły dnia
export type GoalDay = {
  id: number;
  goal_id: number;
  date: string;          
  status: GoalDayStatus; 
  note: string | null;
  created_at: string;    
  attachments: unknown[]; // nie uzywamy teraz
};
//obiekt celu
export type GoalDetails = {
  id: number;
  title: string;
  description: string | null;
  interval_days: number;

  start_date: string;
  end_date: string;

  status: GoalStatus;
  is_public: boolean;

  // narazie nie uzywam
  progress_percent?: number;
  likes_count?: number;

  days: GoalDay[];
};
//pobieamy cel 
export async function getGoal(id: number) {
  return apiGetAuth<GoalDetails>(`/api/goals/${id}`);
}

// dane jakie wysyłamy
export type CreateGoalPayload = {
  goal_category_id: number;
  title: string;
  interval_days: number;
  start_date: string;
  end_date: string;
  description?: string;
  is_public?: boolean;
};

export async function createGoal(payload: CreateGoalPayload) {
  
  return apiPostAuth<GoalDetails>(`/api/goals`, payload);
}

export type UpdateGoalPayload = {
  title?: string;
  description?: string | null;
  is_public?: boolean;
};

export async function updateGoal(goalId: number, payload: UpdateGoalPayload) {
  
  return apiPatchAuth<GoalDetails>(`/api/goals/${goalId}`, payload);
}

// odchaczanie, status dnia
export async function toggleDoneForDate(goalId: number, date: string) {
  
  return apiPatchAuth<GoalDay>(`/api/goals/${goalId}/days/${date}/done`, {});
}



// usuwanie
export async function deleteGoal(goalId: number) {
  return apiDeleteAuth<{ ok: boolean }>(`/api/goals/${goalId}`);
}

// publiczne typ
export type PublicGoal = {
  id: number;
  title: string;
  description?: string | null;
  start_date?: string;
  end_date?: string;
  progress_percent?: number;
  user?: { id: number; name?: string; email?: string };
};


export type PublicGoalsResponse = {
  goals: PublicGoal[];
};

export async function getPublicGoals() {
  return apiGetAuth<PublicGoalsResponse>(`/api/public-goals`);
}