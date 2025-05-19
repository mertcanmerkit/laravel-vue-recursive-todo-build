<template>
    <li class="task-card">
        <div class="task-body d-flex justify-content-between">
            <div class="d-flex align-items-start">
                <input type="checkbox" :checked="task.is_completed" @change="toggleComplete" class="form-check-input me-3 mt-1" />
                <div>
                    <div :class="['fw-semibold text-break', { 'text-decoration-line-through': task.is_completed }]">
                        {{ task.name }}
                    </div>
                    <div class="mt-1 d-flex flex-wrap align-items-center">
            <span class="badge me-2" :class="priorityClass(task.priority)">
              <font-awesome-icon icon="flag" class="me-1" />
              {{ task.priority }}
            </span>
                        <span
                            v-for="label in task.labels"
                            :key="label.name"
                            class="badge label-badge me-2"
                            :style="{ backgroundColor: label.bg_color }"
                        >
              {{ label.name }}
            </span>
                    </div>
                </div>
            </div>
            <div class="task-action-buttons flex-shrink-0 mt-1 text-end">
                <button class="btn btn-sm text-secondary me-2" @click="$emit('edit-task', task)">
                    <font-awesome-icon icon="edit" />
                </button>
                <button class="btn btn-sm text-danger me-2" @click="requestDelete">
                    <font-awesome-icon icon="trash" />
                </button>
                <button class="btn btn-sm text-primary" @click="$emit('add-subtask', task.id)">
                    <font-awesome-icon icon="arrow-turn-down" :flip="'horizontal'" />
                </button>
            </div>
        </div>

        <ul v-if="task.children && task.children.length" class="list-group mt-2 ms-4 subs">
            <TaskItem
                v-for="child in task.children"
                :key="child.id"
                :task="child"
                @refresh="$emit('refresh')"
                @add-subtask="$emit('add-subtask', $event)"
                @edit-task="$emit('edit-task', $event)"
            />
        </ul>

        <ConfirmModal
            :visible="showConfirm"
            title="Delete Task"
            message="Are you sure you want to delete this task?"
            confirm-text="Delete"
            cancel-text="Cancel"
            @confirm="confirmDelete"
            @cancel="showConfirm = false"
        />
    </li>
</template>

<script setup>
import { ref } from 'vue';
import axios from '../axios';
import TaskItem from './TaskItem.vue';
import ConfirmModal from './ConfirmModal.vue';
import { useToast } from 'vue-toast-notification';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps(['task']);
const emit = defineEmits(['refresh', 'add-subtask', 'edit-task']);
const toast = useToast();
const showConfirm = ref(false);

const toggleComplete = async () => {
    await axios.put(`/api/lists/${props.task.todo_list_id}/tasks/${props.task.id}`, {
        is_completed: !props.task.is_completed,
    });
    emit('refresh');
};

const requestDelete = () => {
    showConfirm.value = true;
};

const confirmDelete = async () => {
    await axios.delete(`/api/lists/${props.task.todo_list_id}/tasks/${props.task.id}`);
    emit('refresh');
    toast.success('Task deleted.');
    showConfirm.value = false;
};

const priorityClass = (priority) => {
    switch (priority) {
        case 'high': return 'bg-danger';
        case 'medium': return 'bg-warning';
        case 'low': return 'bg-success';
        default: return 'bg-secondary';
    }
};

const priorityIcon = (priority) => {
    switch (priority) {
        case 'high': return 'arrow-up';
        case 'medium': return 'arrow-left';
        case 'low': return 'check';
        default: return 'check';
    }
};
</script>

<style scoped>
.text-decoration-line-through {
    text-decoration: line-through;
}
.task-card {
    background: #fff;
    border: 1px solid #e2e2e2;
    border-radius: 8px;
    padding: 16px 0;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
}
.task-body{
    padding: 0 16px;
}

.task-card {
    background: #fff;
    border: 1px solid #e2e2e2;
    border-radius: 8px;
    padding: 16px 0;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);

}

.subs.list-group .task-card {
    border-right: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    padding-right: 0;
}

.label-badge {
    color: #fff;
    font-size: 0.75rem;
    border-radius: 10px;
    padding: 3px 8px;
}
</style>
