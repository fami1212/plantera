<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faPlus,
    faEdit,
    faTrash,
    faLeaf,
    faSync,
    faChevronLeft,
    faChevronRight,
    faSeedling,
} from '@fortawesome/free-solid-svg-icons';

// State
const crops = ref([]);
const pagination = ref({});
const searchQuery = ref('');
const showModal = ref(false);
const modalAction = ref('create');
const selectedCrop = ref({ name: '', type: '', status: '' });

// Load crops and pagination data
const { props } = usePage();
if (props.crops) {
    crops.value = props.crops.data || [];
    pagination.value = {
        currentPage: props.crops.current_page,
        lastPage: props.crops.last_page,
        prevPageUrl: props.crops.prev_page_url,
        nextPageUrl: props.crops.next_page_url,
    };
}

// Filter crops
const filteredCrops = computed(() => {
    if (!searchQuery.value) return crops.value;
    return crops.value.filter((crop) =>
        crop.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

// Open modal for create/edit
const openModal = (action, crop = null) => {
    modalAction.value = action;
    selectedCrop.value = crop ? { ...crop } : { name: '', type: '', status: '' };
    showModal.value = true;
};

// Save crop
const saveCrop = () => {
    const url = modalAction.value === 'create' ? '/crops' : `/crops/${selectedCrop.value.id}`;
    const method = modalAction.value === 'create' ? 'post' : 'put';
    router[method](url, selectedCrop.value, {
        preserveScroll: true,
        onSuccess: () => {
            refreshCrops();
            resetModal();
        },
    });
};

// Reset modal state
const resetModal = () => {
    selectedCrop.value = { name: '', type: '', status: '' };
    showModal.value = false;
};

// Delete crop
const deleteCrop = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette culture ?')) {
        router.delete(`/crops/${id}`, {
            preserveScroll: true,
            onSuccess: () => refreshCrops(),
        });
    }
};

// Refresh crops or navigate between pages
const refreshCrops = (url = '/crops') => {
    router.get(url, {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            crops.value = page.props.crops.data || [];
            pagination.value = {
                currentPage: page.props.crops.current_page,
                lastPage: page.props.crops.last_page,
                prevPageUrl: page.props.crops.prev_page_url,
                nextPageUrl: page.props.crops.next_page_url,
            };
        },
    });
};

// Navigate to harvests
const goToHarvests = (crop) => {
    router.get(`/crops/${crop.id}/harvests`);
};
</script>

<template>
    <div class="container mx-auto p-6 bg-gray-50 rounded shadow-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold text-green-700 flex items-center gap-2">
                <FontAwesomeIcon :icon="faLeaf" /> Mes Cultures
            </h1>
            <div class="flex gap-2">
                <button
                    @click="refreshCrops"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2"
                >
                    <FontAwesomeIcon :icon="faSync" /> Actualiser
                </button>
                <button
                    @click="openModal('create')"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2"
                >
                    <FontAwesomeIcon :icon="faPlus" /> Ajouter
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher une culture..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring-2 focus:ring-green-500"
            />
        </div>

        <!-- Table -->
        <div v-if="filteredCrops.length" class="bg-white rounded-lg shadow-md">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="bg-green-700 text-white">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="crop in filteredCrops" :key="crop.id" class="hover:bg-green-50">
                        <td class="px-4 py-3">{{ crop.name }}</td>
                        <td class="px-4 py-3">{{ crop.type }}</td>
                        <td class="px-4 py-3">{{ crop.status }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <button
                                @click="openModal('edit', crop)"
                                class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded flex items-center gap-1"
                            >
                                <FontAwesomeIcon :icon="faEdit" /> Modifier
                            </button>
                            <button
                                @click="deleteCrop(crop.id)"
                                class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded flex items-center gap-1"
                            >
                                <FontAwesomeIcon :icon="faTrash" /> Supprimer
                            </button>
                            <button
                                @click="goToHarvests(crop)"
                                class="bg-purple-500 hover:bg-purple-700 text-white py-1 px-3 rounded flex items-center gap-1"
                            >
                                <FontAwesomeIcon :icon="faSeedling" /> Récoltes
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p v-else class="text-center text-gray-500">Aucune culture trouvée.</p>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <button
                v-if="pagination.prevPageUrl"
                @click="refreshCrops(pagination.prevPageUrl)"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg"
            >
                <FontAwesomeIcon :icon="faChevronLeft" /> Précédent
            </button>
            <span class="text-gray-600 font-semibold">
                Page {{ pagination.currentPage }} sur {{ pagination.lastPage }}
            </span>
            <button
                v-if="pagination.nextPageUrl"
                @click="refreshCrops(pagination.nextPageUrl)"
                class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg"
            >
                Suivant <FontAwesomeIcon :icon="faChevronRight" />
            </button>
        </div>

        <!-- Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded shadow-lg">
                <h2 class="text-xl font-bold mb-4">
                    {{ modalAction === 'create' ? 'Ajouter une culture' : 'Modifier une culture' }}
                </h2>
                <div class="mb-4">
                    <label>Nom</label>
                    <input
                        v-model="selectedCrop.name"
                        type="text"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    />
                </div>
                <div class="mb-4">
                    <label>Type</label>
                    <input
                        v-model="selectedCrop.type"
                        type="text"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    />
                </div>
                <div class="mb-4">
                    <label>Statut</label>
                    <select
                        v-model="selectedCrop.status"
                        class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-green-500"
                    >
                        <option value="Planté">Planté</option>
                        <option value="Récolté">Récolté</option>
                        <option value="Vendu">Vendu</option>
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
                        @click="saveCrop"
                        class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700"
                    >
                        {{ modalAction === 'create' ? 'Ajouter' : 'Modifier' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
