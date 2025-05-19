<template>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Your Lists</h2>
            <div>
                <span class="me-3 fw-bold">Welcome, {{ currentUser.name }}</span>
                <button class="btn btn-primary btn-sm" @click="showCreateModal = true">
                    <font-awesome-icon icon="plus" class="me-1"/>
                    New List
                </button>
            </div>
        </div>

        <div v-if="lists.length === 0" class="alert alert-info text-center">No lists found.</div>

        <div class="row">
            <div class="col-md-4 mb-3" v-for="list in lists" :key="list.id">
                <div class="card h-100 shadow-sm cursor-pointer" @click="goToListTasks(list.id)">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1 text-break">
                                    {{ list.name.length > 50 ? list.name.slice(0, 50) + '...' : list.name }}
                                </h5>
                            </div>

                            <div class="text-end" v-if="!showCreateModal && !editingList">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-sm btn-light border-0"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        @click.stop
                                    >
                                        <font-awesome-icon icon="ellipsis-vertical"/>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" @click.stop.prevent="editList(list)">
                                                <font-awesome-icon icon="edit" class="me-2"/>
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#"
                                               @click.stop.prevent="requestDelete(list.id)">
                                                <font-awesome-icon icon="trash" class="me-2"/>
                                                Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">
                            {{ list.completed_tasks }} / {{ list.total_tasks }} completed
                        </small>
                        <div class="progress mt-1" style="height: 6px;">
                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                :style="{ width: progressPercent(list) + '%' }"
                                :aria-valuenow="progressPercent(list)"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showCreateModal || editingList" class="modal-backdrop">
            <div
                class="modal-content p-4 bg-white border rounded shadow-sm"
                style="max-width: 400px; margin: 100px auto;"
            >
                <h5>{{ editingList ? 'Edit List' : 'Create List' }}</h5>
                <input
                    ref="nameInput"
                    v-model="form.name"
                    type="text"
                    class="form-control my-3"
                    placeholder="List name"
                />
                <div class="d-flex justify-content-end">
                    <button class="btn btn-secondary me-2" @click="resetForm">Cancel</button>
                    <button class="btn btn-primary" @click="submitForm">
                        <font-awesome-icon :icon="editingList ? 'edit' : 'plus'" class="me-1"/>
                        {{ editingList ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>

        <ConfirmModal
            :visible="showConfirm"
            title="Delete List"
            message="Are you sure you want to delete this list?"
            confirm-text="Delete"
            cancel-text="Cancel"
            @confirm="confirmDelete"
            @cancel="showConfirm = false"
        />
    </div>
</template>

<script setup>
import {ref, onMounted, watch, nextTick} from 'vue';
import axios from '../axios';
import {useRouter} from 'vue-router';
import ConfirmModal from '../components/ConfirmModal.vue';
import {currentUser} from '../auth';
import {useToast} from 'vue-toast-notification';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

const router = useRouter();
const toast = useToast();

const lists = ref([]);
const form = ref({name: ''});
const showCreateModal = ref(false);
const editingList = ref(null);
const showConfirm = ref(false);
const pendingDeleteId = ref(null);
const nameInput = ref(null);

const fetchLists = async () => {
    const res = await axios.get('/api/lists');
    lists.value = res.data;
};

const submitForm = async () => {
    if (editingList.value) {
        await axios.put(`/api/lists/${editingList.value.id}`, form.value);
        toast.success('List updated successfully.');
    } else {
        await axios.post('/api/lists', form.value);
        toast.success('List created successfully.');
    }
    await fetchLists();
    resetForm();
};

const editList = (list) => {
    editingList.value = list;
    form.value.name = list.name;
    showCreateModal.value = true;
};

const requestDelete = (id) => {
    pendingDeleteId.value = id;
    showConfirm.value = true;
};

const confirmDelete = async () => {
    await axios.delete(`/api/lists/${pendingDeleteId.value}`);
    toast.success('List deleted successfully.');
    await fetchLists();
    showConfirm.value = false;
};

const resetForm = () => {
    editingList.value = null;
    showCreateModal.value = false;
    form.value.name = '';
};

const goToListTasks = (listId) => {
    router.push(`/lists/${listId}/tasks`);
};

const progressPercent = (list) => {
    if (!list.total_tasks) return 0;
    return Math.round((list.completed_tasks / list.total_tasks) * 100);
};

watch([showCreateModal, editingList], async ([show, edit]) => {
    if (show || edit) {
        await nextTick();
        nameInput.value?.focus();
    }
});

onMounted(fetchLists);
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
</style>
