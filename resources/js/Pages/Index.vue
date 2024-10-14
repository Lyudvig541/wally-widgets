<template>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Pack name
                </th>
                <th scope="col" class="px-6 py-3">
                    Widgets count
                </th>

                <th scope="col" class="px-6 py-3 flex justify-between items-center">
                    Action
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                    focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2
                    dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            @click="addPack">New pack
                    </button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="pack in packs" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">

                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ pack.name }}
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ pack.count_of_widgets }}
                </td>
                <td class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white flex gap-2">
                    <button type="button" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4
                     focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2"
                            @click="editPack(pack.id)">Edit
                    </button>
                    <button @click="deletePack(pack.id)" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800
             focus:ring-4 focus:ring-red-300 font-medium rounded-lg
             text-sm px-5 py-2.5 me-2 mb-2">Delete
                    </button>
                </td>

            </tr>
            </tbody>
        </table>
    </div>

    <div v-if="packs.length>0" class="flex flex-row justify-center mt-5">
        <form @submit.prevent="getWidgets">

            <div class="flex flex-col gap-4">
                <h2 v-if="result" class="w-[400px] mb-1 text-xl font-medium text-gray-900 text center ">{{ result }}</h2>
                <h2 v-else class="w-[400px] mb-1 text-xl font-medium text-gray-900 text center ">Count of Widgets that you want</h2>

                <input type="number" id="count" v-model="widgetsCount"
                       class="bg-gray-50 border border-gray-300 text-gray-900
                text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required/>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
                    focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2
                    dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get Widgets
                </button>
            </div>
        </form>

    </div>


</template>
<script setup>
import {onMounted, ref} from 'vue';
import axios from "axios";
import {useRouter} from "vue-router";

const packs = ref([]);
const widgetsCount = ref();
const result = ref('');
const router = useRouter();

onMounted(async () => {
    await fetchPacks();
});
const fetchPacks = async () => {
    await axios.get('/api/getAll').then((res) => {
        console.log(res.data)
        packs.value = res.data.data; // Assign fetched packs to 'packs' ref
    }).catch((err) => {
        console.log('Error:', err.message);
    });
};
const deletePack = async (id) => {
    await axios.delete(`/api/delete/${id}`).then(() => {
        packs.value = packs.value.filter(pack => pack.id !== id);
    });
};
const addPack = () => {
    router.push("/new")
}
const editPack = (id) => {
    router.push(`/edit/${id}`)
}
const getWidgets = async () => {
    result.value ='Loading...';

    await axios.post('/api/order', {
        count: widgetsCount.value
    }).then((res) => {
        result.value =''
        result.value += "You will get "
        for (const [key, value] of Object.entries(res.data.result)) {
            result.value += value + " pack of " + key + " widgets, "
        }
        console.log(result.value, 22222);
    }).catch((err) => {
        console.log('Error:', err.message);
    });
}
</script>
