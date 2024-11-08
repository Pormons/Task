<template>
  <Toaster />
  <div class="min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto mb-8">
      <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="w-full sm:w-72">
          <Input v-model="search" @keydown.enter="searchTask" type="email" placeholder="Search Task" class="w-full" />
        </div>
        <CreateTask :fetchTasks="fetchAllTasks" />
      </div>
    </div>

    <!-- Toggle Filter Section -->
    <div class="max-w-7xl mx-auto mb-8 overflow-x-auto">
      <ToggleGroup :modelValue="selectedOption" @update:modelValue="handleToggleChange" type="single"
        class="inline-flex w-full sm:w-auto justify-start p-1 space-x-1">
        <ToggleGroupItem value="all" class="flex-1 sm:flex-none">All</ToggleGroupItem>
        <ToggleGroupItem value="pending" class="flex-1 sm:flex-none">Pending</ToggleGroupItem>
        <ToggleGroupItem value="inprogress" class="flex-1 sm:flex-none">In Progress</ToggleGroupItem>
        <ToggleGroupItem value="completed" class="flex-1 sm:flex-none">Completed</ToggleGroupItem>
      </ToggleGroup>
    </div>

    <!-- Main Content -->
    <main class="min-w-7xl mx-auto px-4 sm:px-6">
      <div v-if="filteredTasks.length === 0" class="flex items-center h-full justify-center">
        <span class="text-4xl font-extrabold text-primary/50">
          NO TASKS YET...
        </span>
      </div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-10 gap-4">
        <TaskCard :fetchTasks="fetchAllTasks" :taskss="tasks" v-for="task in filteredTasks" :key="task.id"
          v-bind="task" />
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { TaskData } from './interfaces/task'
import { ref, onMounted } from 'vue'
import { fetchTasks } from './api/services'
import { Input } from './components/ui/input';
import { Toaster } from './components/ui/toast';
import ToggleGroup from './components/ui/toggle-group/ToggleGroup.vue';
import ToggleGroupItem from './components/ui/toggle-group/ToggleGroupItem.vue';
import TaskCard from './components/TaskCard.vue';
import CreateTask from './components/CreateTask.vue';


type FilterOption = 'all' | 'pending' | 'inprogress' | 'completed'
const selectedOption = ref<FilterOption>('all')

const tasks = ref<TaskData[]>([]);
const filteredTasks = ref(tasks.value);
const fetchAllTasks = async () => {
  console.log('fetching')
  try {
    const response = await fetchTasks()
    tasks.value = response.data

    // Reapply the filter after fetching tasks
    if (selectedOption.value !== 'all') {
      filteredTasks.value = tasks.value.filter(task => task.status?.toLowerCase().includes(selectedOption.value))
    } else {
      filteredTasks.value = tasks.value
    }
  } catch (error) {
    console.log(error)
  }
}


const search = ref('');
const searchTask = () => {
  const query = search.value.toLowerCase();

  // First, filter based on the search query
  let filtered = tasks.value.filter(task => task.title?.toLowerCase().includes(query));

  // Then, apply the selected filter option
  if (selectedOption.value !== 'all') {
    filtered = filtered.filter(task => task.status?.toLowerCase().includes(selectedOption.value));
  }

  // Update filteredTasks to reflect both the search and the selected filter option
  filteredTasks.value = filtered;
};

const handleToggleChange = (payload: string | string[]) => {
  // If newValue is undefined keep the current value
  if (payload !== undefined) {
    selectedOption.value = payload as FilterOption
    if (payload !== 'all') {
      filteredTasks.value = tasks.value.filter(task => task.status?.toLowerCase().includes(payload as string))
      return;
    }
    filteredTasks.value = tasks.value
  }
};

onMounted(() => {
  fetchAllTasks()
})

</script>
