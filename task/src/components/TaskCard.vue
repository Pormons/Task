<template>
    <Dialog @update:open="dialogChange">
        <Card class="h-full flex flex-col justify-between">
            <CardHeader>
                <div class="flex items-center gap-2 justify-between">
                    <DialogTrigger>
                        <CardTitle class="w-36 md:w-28 truncate text-start">
                            <span v-text="props.title"></span>
                        </CardTitle>
                    </DialogTrigger>
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <button>
                                <EllipsisVertical />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent>
                            <DropdownMenuItem v-show="['pending', 'completed'].includes(props.status)"
                                @click.prevent.stop="statusUpdate('inprogress')" class="text-yellow-500">
                                <CircleDotDashed />
                                In Progress
                            </DropdownMenuItem>
                            <DropdownMenuItem v-show="['pending', 'inprogress'].includes(props.status)"
                                @click.prevent.stop="statusUpdate('completed')" class="text-green-500">
                                <CircleCheckBig />
                                Completed
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem @click.prevent.stop="taskDelete" class="text-red-500">
                                <Trash2 />
                                Delete
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                </div>
                <CardDescription class="line-clamp-4 break-words">
                    <span v-text="props.description"></span>
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Separator class="mb-2" />
                <div :class="[
                    'capitalize p-1 px-2 rounded-full font-semibold text-xs text-primary-foreground flex items-center gap-2',
                    {
                        'bg-primary': props.status === 'pending',
                        'bg-yellow-500': props.status === 'inprogress',
                        'bg-green-500': props.status === 'completed'
                    }
                ]">
                    <CircleDashed class="h-4 w-4" v-if="props.status === 'pending'" />
                    <CircleDotDashed class="h-4 w-4" v-else-if="props.status === 'inprogress'" />
                    <CircleCheckBig class="h-4 w-4" v-else-if="props.status === 'completed'" />
                    <span>
                        {{ props.status }}
                    </span>
                </div>
            </CardContent>
        </Card>
        <DialogContent>
            <form v-if="editMode" @submit.prevent.stop="editTask" class="flex flex-col gap-2">
                <Label for="title">Title</Label>
                <Input minlength="5" id="title" v-model="task.title" type="text" placeholder="Title" />
                <Label for="description">Description</Label>
                <Textarea minlength="5" id="description" v-model="task.description" placeholder="Description" />
                <Label for="status">Status</Label>
                <Select v-model="task.status" id="status">
                    <SelectTrigger>
                        <SelectValue placeholder="status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem value="pending">
                                Pending
                            </SelectItem>
                            <SelectItem value="inprogress">
                                In Progress
                            </SelectItem>
                            <SelectItem value="completed">
                                Completed
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <DialogFooter>
                    <Button type="submit" class="bg-green-600">
                        Save
                    </Button>
                </DialogFooter>
            </form>
            <div v-else class="flex flex-col items-start gap-2">
                <span class="flex h-9 w-full bg-transparent py-1 text-lg font-extrabold transition-colors"
                    v-text="props.title">
                </span>
                <span class="text-xs" v-text="formatTimestamp(props.created_at)">
                </span>
                <Separator />
                <Label for="task_description">Description</Label>
                <span id="task_description"
                    class="flex h-9 w-full bg-transparent py-1 text-sm font-normal transition-colors"
                    v-text="props.description">
                </span>
                <Separator class="mb-2" />
                <div :class="[
                    'capitalize p-1 px-2 rounded-full font-semibold text-xs text-primary-foreground flex items-center gap-1',
                    {
                        'bg-primary': props.status === 'pending',
                        'bg-yellow-500': props.status === 'inprogress',
                        'bg-green-500': props.status === 'completed'
                    }
                ]">
                    <CircleDashed class="h-4 w-4" v-if="props.status === 'pending'" />
                    <CircleDotDashed class="h-4 w-4" v-else-if="props.status === 'inprogress'" />
                    <CircleCheckBig class="h-4 w-4" v-else-if="props.status === 'completed'" />
                    <span>
                        {{ props.status }}
                    </span>
                </div>
            </div>
            <DialogFooter>
                <Button v-if="!editMode" @click="editMode = true" class="bg-orange-500 flex gap-2">
                    <Pencil class="h-4 w-4" />
                    Edit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { Task, TaskData } from '@/interfaces/task';
import { ref } from 'vue';
import { defineProps } from 'vue'
import { Dialog, DialogContent, DialogFooter, DialogTrigger } from './ui/dialog';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from './ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from './ui/dropdown-menu';
import { CircleCheckBig, CircleDashed, CircleDotDashed, EllipsisVertical, Pencil, Trash2 } from 'lucide-vue-next';
import { Separator } from './ui/separator';
import { Label } from './ui/label';
import { Input } from './ui/input';
import { Textarea } from './ui/textarea';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from './ui/select';
import { Button } from './ui/button';
import { deleteTask, updateStatusTask, updateTask } from '../api/services';
import { useToast } from './ui/toast';

const props = defineProps<{
    id: TaskData['id'];
    title: TaskData['title'];
    description: TaskData['description'];
    status: TaskData['status'];
    created_at: TaskData['created_at'];
    updated_at: TaskData['updated_at'];
    fetchTasks: () => void;
}>()


const { toast } = useToast()
const task = ref<Task>({
    title: props.title,
    description: props.description,
    status: props.status
})

// If Dialog closes and editmode is still true, turn editMode to false
const dialogChange = (dialogStatus: boolean) => {
    if (!dialogStatus) {
        if (editMode) {
            editMode.value = false;
        }
    }
}


// Convert Timestamp to readable date format
const formatTimestamp = (timestamp: string) => {
    const date = new Date(timestamp)
    return date.toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    })
}

const editMode = ref<boolean>(false)

const editTask = async () => {
    try {
        const response = await updateTask(props.id, task.value);
        console.log(response.data)
        await props.fetchTasks();
        toast({
            variant: 'success',
            title: 'Task Updated'
        })
        editMode.value = false
    } catch (error) {
        toast({
            variant: 'destructive',
            title: 'Something went wrong'
        })
        console.log(error);
    }
}

const statusUpdate = async (status: string) => {
    try {
        const response = await updateStatusTask(props.id, status);
        await props.fetchTasks();
        toast({
            variant: 'success',
            title: 'Status Updated'
        })
    } catch (error) {
        toast({
            variant: 'destructive',
            title: 'Something went wrong'
        })
    }
}

const taskDelete = async () => {
    try {
        const response = await deleteTask(props.id);
        await props.fetchTasks();
        toast({
            variant: 'success',
            title: 'Task Deleted'
        })
    } catch (error) {
        toast({
            variant: 'destructive',
            title: 'Something went wrong'
        })
    }
}
</script>
