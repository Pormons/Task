export interface Task {
  title: string;
  description: string;
  status: string;
}

export interface TaskData extends Task {
  id: number;
  created_at: string;
  updated_at: string;
}
