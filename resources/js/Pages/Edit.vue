<template>

    <form  @submit.prevent="submitForm" class="w-full flex flex-col justify-center items-center">
        <div class="flex flex-row mt-5" >
            <h1 v-if="pack" class="mb-1 text-2xl font-medium text-gray-900" >Edit {{ pack.name }}</h1>
            <h1 v-else class="mb-1 text-2xl font-medium text-gray-900">Loading...</h1>

        </div>
        <div class="flex flex-row gap-6 mb-6 ">
            <div class="flex flex-col">
                <label for="name" class="block mb-1 text-sm font-medium text-gray-900">Pack Name</label>
                <input type="text" id="name" v-model="packName"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm
               rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/></div>
            <div class="flex flex-col">
                <label for="count" class="block mb-1 text-sm font-medium text-gray-900 ">Count of Widgets</label>
                <input type="number" id="count" v-model="widgetsCount"
                       class="bg-gray-50 border border-gray-300 text-gray-900
                text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
            </div>

        </div>
        <div class="flex flex-row " >
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
            focus:outline-none focus:ring-blue-300 font-medium rounded-lg
            text-sm w-full sm:w-auto px-5 py-2.5 me-2 mb-2 text-center">Edit</button>
            <button @click="goHome" type="button" class="text-white bg-gray-800 hover:bg-gray-900
             focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm
              px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700">Home</button>
        </div>
    </form>

</template>

<script setup>
import {defineProps, onMounted, ref} from 'vue';
import {useRoute, useRouter} from "vue-router";
import axios from 'axios';


// Define props directly
const props = defineProps({
    message: {
        type: String,
        default: 'Welcome to the Home Page!',
    },
});


const route = useRoute();
const id = route.params.id;

const pack = ref(null);
const packName = ref(null);
const widgetsCount = ref(null);
const router = useRouter();
onMounted(async () => {
        console.log(5555)
        await axios.get(`/api/edit/${id}`,{
        }).then((res) =>{
            pack.value = res.data.data;
            packName.value = res.data.data.name;
            widgetsCount.value = res.data.data.count_of_widgets;
        }).catch((err)=>{
            console.log('Error:', err.message);
        });
});


const submitForm = async () => {
        const response = await axios.post('/api/update', {
            id:id,
            name: packName.value,
            count_of_widgets:widgetsCount.value,
        }).then(()=>{
            router.push("/")
        }).catch((err)=>{
            console.log('Error:', err.message);
        })
}
const goHome = async () => {
    router.push("/")
}
</script>

<style scoped>
/* Your styles here */
</style>
