import { Task } from "../interfaces/task";
import apiClient from "./axios";

export const fetchTasks = () => apiClient.get("/tasks");
export const addTask = (task: Task) => apiClient.post("/task", task);
export const updateTask = (id: number, task: Task) =>
  apiClient.patch(`/task/${id}`, task);
export const updateStatusTask = (id: number, status: string) =>
  apiClient.patch(`/task/status/${id}`, {status : status});
export const deleteTask = (id: number) => apiClient.delete(`/task/${id}`);
