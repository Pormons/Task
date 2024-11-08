<template>
    <Drawer @update:open="handleDrawerChange" :open="isOpen">
        <DrawerTrigger as-child>
            <Button class="w-full bg-green-600 text-white hover:bg-green-600/50 gap-2 sm:w-auto">
                <BadgePlus />
                <span>
                    Add Task
                </span>
            </Button>
        </DrawerTrigger>
        <DrawerContent>
            <div class="mx-auto w-full max-w-sm p-4">
                <DrawerHeader>
                    <DrawerTitle>Add Task</DrawerTitle>
                </DrawerHeader>
                <form class="flex flex-col gap-4 mb-6" @submit.prevent="createTask">
                    <Input required minlength="5" v-model="taskForm.title" type="text" placeholder="Title" />
                    <Textarea required minlength="5" v-model="taskForm.description" placeholder="Description" />
                    <Button :disabled="isSending" type="submit">
                        <div class="flex items-center justify-center gap-2">
                            <LoaderCircle v-show="isSending"
                                class="w-5 h-5 animate-spin text-gray-200 dark:text-gray-600" />
                            <span v-if="isSending">Creating Task...</span>
                            <span v-else>Create Task</span>
                        </div>
                    </Button>
                </form>
            </div>
        </DrawerContent>
    </Drawer>
</template>

<script setup lang="ts">
import { BadgePlus, LoaderCircle } from 'lucide-vue-next';
import { Button } from './ui/button';
import { Drawer, DrawerContent, DrawerHeader, DrawerTitle, DrawerTrigger } from './ui/drawer';
import { Input } from './ui/input';
import { Textarea } from './ui/textarea';
import { ref } from 'vue';
import { useToast } from './ui/toast';
import { Task } from '@/interfaces/task';
import { addTask } from '../api/services';

const props = defineProps<{
    fetchTasks: () => void;
}>()

const { toast } = useToast()
const isSending = ref<boolean>(false);
const taskForm = ref<Task>({
    title: '',
    description: '',
    status: ''
})

const isOpen = ref<boolean>(false);
const handleDrawerChange = (newState: boolean) => {
    isOpen.value = newState;
};

const createTask = async () => {
    try {
        isSending.value = true;
        const response = await addTask(taskForm.value);
        console.log(response.data)
        await props.fetchTasks();
        toast({
            variant: 'success',
            title: 'Task Created'
        })
        isOpen.value = false;
        taskForm.value = {
            title: '',
            description: '',
            status: ''
        }
    } catch (error) {
        toast({
            variant: 'destructive',
            title: 'Something Went Wrong'
        })
    }
    isSending.value = false;
}

</script>
