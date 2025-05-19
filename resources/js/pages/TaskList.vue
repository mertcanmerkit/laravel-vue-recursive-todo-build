<template>
    <div class="container mt-5">
        <div class="d-lg-flex justify-content-between align-items-end mb-3">
            <h2>{{ listName }}</h2>
            <div class="flex-shrink-0">
                <router-link to="/" class="btn btn-outline-secondary btn-sm me-2">← My Lists</router-link>
                <button class="btn btn-sm btn-primary" @click="openCreateModal()">New Task</button>
            </div>
        </div>

        <div v-if="tasks.length === 0" class="text-muted">No tasks found.</div>

        <ul class="list-group">
            <TaskItem
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @refresh="fetchTasks"
                @add-subtask="openCreateModal"
                @edit-task="openEditModal"
            />
        </ul>

        <div v-if="showCreateModal" class="modal-backdrop">
            <div class="modal-content p-4 bg-white border rounded shadow-sm" style="max-width: 500px; margin: 100px auto;">
                <h5>{{ isEditing ? 'Edit Task' : 'Create Task' }}</h5>

                <input v-model="form.name" class="form-control my-2" placeholder="Task name" />

                <label class="form-label mt-3">Priority</label>
                <div class="input-group">
                  <span class="input-group-text border-0 text-white" :class="priorityColorClass">
                    <font-awesome-icon icon="flag" />
                  </span>
                    <select
                        v-model="form.priority"
                        class="form-select border-0 text-white no-arrow"
                        :class="priorityColorClass"
                        style="outline: none; box-shadow: none;"
                    >
                        <option disabled value="">Select priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>


                <label class="mt-2">Labels</label>
                <div class="d-flex flex-wrap mb-2">
          <span
              v-for="(label, index) in form.label_names"
              :key="index"
              class="badge me-2 mb-2"
              :style="{ backgroundColor: label.bg_color, color: '#fff' }"
          >
            {{ label.name }}
            <button type="button" class="btn-close btn-close-white btn-sm ms-2" @click="removeLabel(index)"></button>
          </span>
                </div>

                <input
                    v-model="labelInput"
                    class="form-control"
                    placeholder="Type label and press enter or comma"
                    @keydown.enter.prevent="addLabel"
                    @keydown.,.prevent="addLabel"
                    @keydown.space.exact.prevent
                />

                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-secondary me-2" @click="resetForm">Cancel</button>
                    <button class="btn btn-primary" @click="submitTask">
                        {{ isEditing ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import axios from '../axios';
import TaskItem from '../components/TaskItem.vue';
import {useToast} from 'vue-toast-notification';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const listId = route.params.listId;

const listName = ref('');
const tasks = ref([]);

const showCreateModal = ref(false);
const labelInput = ref('');
const isEditing = ref(false);
const editingTaskId = ref(null);

const form = ref({
    name: '',
    priority: 'low',
    parent_id: null,
    label_names: [],
});

const fetchTasks = async () => {
    const res = await axios.get(`/api/lists/${listId}/tasks`);
    tasks.value = res.data;
};

const fetchListName = async () => {
    const res = await axios.get(`/api/lists/${listId}`);
    listName.value = res.data.name;
};

const submitTask = async () => {
    if (isEditing.value) {
        await axios.put(`/api/lists/${listId}/tasks/${editingTaskId.value}`, form.value);
        toast.success('Task updated successfully.');
    } else {
        await axios.post(`/api/lists/${listId}/tasks`, form.value);
        toast.success('Task created successfully.');
    }
    await fetchTasks();
    resetForm();
};

const openCreateModal = (parentId = null) => {
    form.value = {
        name: '',
        priority: 'low',
        parent_id: parentId,
        label_names: [],
    };
    labelInput.value = '';
    showCreateModal.value = true;
    isEditing.value = false;
};

const openEditModal = (task) => {
    editingTaskId.value = task.id;
    form.value = {
        name: task.name,
        priority: task.priority,
        parent_id: task.parent_id,
        label_names: task.labels.map(l => ({name: l.name, bg_color: l.bg_color})),
    };
    labelInput.value = '';
    isEditing.value = true;
    showCreateModal.value = true;
};

const priorityColorClass = computed(() => {
    switch (form.value.priority) {
        case 'high':
            return 'bg-danger';
        case 'medium':
            return 'bg-warning';
        case 'low':
            return 'bg-success';
        default:
            return '';
    }
});

const generatePastelColor = () => {
    const hue = Math.floor(Math.random() * 360);
    const saturation = 70 + Math.random() * 10;
    const lightness = 55 + Math.random() * 10;
    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
};

const addLabel = () => {
    const cleaned = labelInput.value
        .split(/,|\n/)
        .map((l) => l.trim())
        .filter((l) => l.length > 0);

    for (const name of cleaned) {
        const exists = form.value.label_names.find((l) => l.name === name);
        if (!exists) {
            form.value.label_names.push({
                name,
                bg_color: generatePastelColor(),
            });
        }
    }

    labelInput.value = '';
};

const removeLabel = (index) => {
    form.value.label_names.splice(index, 1);
};

const resetForm = () => {
    form.value = {
        name: '',
        priority: 'low',
        parent_id: null,
        label_names: [],
    };
    labelInput.value = '';
    showCreateModal.value = false;
    isEditing.value = false;
    editingTaskId.value = null;
};

onMounted(() => {
    fetchListName();
    fetchTasks();
});
</script>
