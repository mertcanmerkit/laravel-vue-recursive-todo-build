<template>
    <div class="modal-backdrop" v-if="visible">
        <div class="modal-content p-4 bg-white border rounded shadow-sm" style="max-width: 400px; margin: 100px auto;">
            <h5 class="mb-3">{{ title }}</h5>
            <p>{{ message }}</p>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-secondary me-2" @click="cancel">{{ cancelText }}</button>
                <button class="btn btn-danger" @click="confirm">{{ confirmText }}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    visible: Boolean,
    title: { type: String, default: 'Confirm Action' },
    message: { type: String, default: 'Are you sure?' },
    confirmText: { type: String, default: 'Yes' },
    cancelText: { type: String, default: 'Cancel' },
});

const emit = defineEmits(['confirm', 'cancel']);

const confirm = () => emit('confirm');
const cancel = () => emit('cancel');
</script>
