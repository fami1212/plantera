<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faPlus,
    faEdit,
    faTrash,
    faArrowLeft,
    faCheckCircle,
    faClock,
} from '@fortawesome/free-solid-svg-icons';

// State variables
const { props } = usePage();
const harvests = ref(props.harvests.data || []);
const pagination = ref(props.harvests || {});
const crop = ref(props.crop);
const showModal = ref(false);
const modalAction = ref('create');
const selectedHarvest = ref({ harvest_date: '', quantity: 0, status: 'pending' });

// Open modal for create/edit
const openModal = (action, harvest = null) => {
    modalAction.value = action;
    selectedHarvest.value = harvest
        ? { ...harvest }
        : { harvest_date: '', quantity: 0, status: 'pending' };
    showModal.value = true;
};

// Refresh harvests after any operation
const refreshHarvests = (url = `/crops/${crop.value.id}/harvests`) => {
    router.get(url, {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            harvests.value = page.props.harvests.data || [];
            pagination.value = page.props.harvests || {};
        },
    });
};

// Save harvest (create or edit)
const saveHarvest = () => {
    const url =
        modalAction.value === 'create'
            ? `/crops/${crop.value.id}/harvests`
            : `/crops/${crop.value.id}/harvests/${selectedHarvest.value.id}`;
    const method = modalAction.value === 'create' ? 'post' : 'put';

    router[method](url, selectedHarvest.value, {
        preserveScroll: true,
        onSuccess: () => {
            resetModal();
            refreshHarvests();
        },
    });
};

// Delete harvest
const deleteHarvest = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette récolte ?')) {
        router.delete(`/crops/${crop.value.id}/harvests/${id}`, {
            preserveScroll: true,
            onSuccess: () => refreshHarvests(),
        });
    }
};

// Reset modal
const resetModal = () => {
    selectedHarvest.value = { harvest_date: '', quantity: 0, status: 'pending' };
    showModal.value = false;
};

// Go back to crops page
const goBackToCrops = () => {
    router.get('/crops');
};
</script>

<template>
    <div class="container mx-auto p-6 bg-gray-50 rounded shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-green-700 flex items-center gap-2">
                Récoltes pour <span class="text-gray-700">{{ crop.name }}</span>
            </h1>
            <button
                @click="goBackToCrops"
                class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded flex items-center gap-2"
            >
                <FontAwesomeIcon :icon="faArrowLeft" /> Retour aux cultures
            </button>
        </div>

        <!-- Add Harvest Button -->
        <div class="mb-4">
            <button
                @click="openModal('create')"
                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg flex items-center gap-2 shadow"
            >
                <FontAwesomeIcon :icon="faPlus" /> Ajouter une Récolte
            </button>
        </div>

        <!-- Harvest Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-left text-gray-600">
                <thead class="bg-green-700 text-white">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Quantité (kg)</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="harvest in harvests"
                        :key="harvest.id"
                        class="hover:bg-green-50 transition duration-150"
                    >
                        <td class="border px-4 py-2">{{ harvest.harvest_date }}</td>
                        <td class="border px-4 py-2">{{ harvest.quantity }}</td>
                        <td class="border px-4 py-2">
                            <span
                                class="inline-flex items-center gap-1 py-1 px-3 rounded text-sm"
                                :class="{
                                    'bg-yellow-100 text-yellow-600': harvest.status === 'pending',
                                    'bg-green-100 text-green-600': harvest.status === 'completed',
                                }"
                            >
                                <FontAwesomeIcon
                                    :icon="harvest.status === 'pending' ? faClock : faCheckCircle"
                                />
                                {{ harvest.status === 'pending' ? 'En cours' : 'Terminé' }}
                            </span>
                        </td>
                        <td class="border px-4 py-2 flex gap-2">
                            <button
                                @click="openModal('edit', harvest)"
                                class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded flex items-center gap-1 shadow"
                            >
                                <FontAwesomeIcon :icon="faEdit" /> Modifier
                            </button>
                            <button
                                @click="deleteHarvest(harvest.id)"
                                class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded flex items-center gap-1 shadow"
                            >
                                <FontAwesomeIcon :icon="faTrash" /> Supprimer
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <button
                v-if="pagination.prev_page_url"
                @click="refreshHarvests(pagination.prev_page_url)"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg"
            >
                Précédent
            </button>
            <span class="text-gray-600 font-semibold">
                Page {{ pagination.current_page }} sur {{ pagination.last_page }}
            </span>
            <button
                v-if="pagination.next_page_url"
                @click="refreshHarvests(pagination.next_page_url)"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg"
            >
                Suivant
            </button>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow-lg w-1/3">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    {{ modalAction === 'create' ? 'Ajouter une Récolte' : 'Modifier une Récolte' }}
                </h2>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Date de Récolte</label>
                    <input
                        v-model="selectedHarvest.harvest_date"
                        type="date"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Quantité (kg)</label>
                    <input
                        v-model="selectedHarvest.quantity"
                        type="number"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Statut</label>
                    <select
                        v-model="selectedHarvest.status"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    >
                        <option value="pending">En cours</option>
                        <option value="completed">Terminé</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button
                        @click="resetModal"
                        class="bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400"
                    >
                        Annuler
                    </button>
                    <button
                        @click="saveHarvest"
                        class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700"
                    >
                        {{ modalAction === 'create' ? 'Ajouter' : 'Modifier' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
