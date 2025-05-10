<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {ref, onMounted} from "vue";
import Swal from "sweetalert2";
import axios from "axios";


const init_date = ref(null)
const end_date = ref(null)
const loading = ref(false)
const currentPage = ref(1)
const lastPage = ref(1)
const paginatedData = ref([])


const mapPaginatedData = (data) => {
    //group all the same ids

    const grouped = {}

    data.forEach(item => {
        if ( !grouped[item.id]) grouped[item.id] = []
        grouped[item.id].push(item)
    })

    let paginatedMappedData = []

    Object.entries(grouped).forEach(([id, itemGroup ]) => {
        itemGroup.forEach((item, idx) => {
            paginatedMappedData.push(
                {
                    ...item,
                    showId: idx === 0,
                    rowspan: idx === 0?  itemGroup.length: null
                }
            )
        })
    })

    console.log(paginatedMappedData)
    paginatedData.value = paginatedMappedData
}

const loadTable = async(page = 1) => {
    loading.value = true

   try{
       const result = await axios.get(`/api/exchange-rate?page=${page}`)
       const paginateValues = result.data.data
       loading.value = false
       currentPage.value = paginateValues.current_page
       lastPage.value = paginateValues.last_page

       mapPaginatedData(paginateValues.data)

   }catch (err){
        console.log(err)
   }
}

const sendForm = async() => {

    if ( init_date.value == null ) {
        Swal.fire({
            icon: "error",
            title: "Fecha incorrecta",
            text: "Tienes que ingresar la fecha inicial",
        });
        return
    }
    if ( end_date.value == null) {
        Swal.fire({
            icon: "error",
            title: "Fecha incorrecta",
            text: "Tienes que ingresar la fecha final",
        });
        return
    }

    if ( init_date.value > end_date.value ){
        Swal.fire({
            icon: "error",
            title: "Fecha incorrecta",
            text: "La fecha inicial no debe ser mayor a la final",
        });
        return
    }
    loading.value = true


    const [year, month, day] = init_date.value.split('-')

    const formatted_init_date = `${day}/${month}/${year}`

    const [year_end, month_end, day_end] = end_date.value.split('-')

    const formatted_end_date = `${day_end}/${month_end}/${year_end}`

    const request = await axios.post('/api/exchange-rate-range', {
        "fechainit": formatted_init_date,
        "fechafin": formatted_end_date
    })
    loading.value = false
    if ( request.data.status ) {
        Swal.fire({
            title: "Busqueda exitosa",
            text: "Puedes validar la data en la tabla proporcionada!",
            icon: "success"
        });

        await loadTable()
    }
}

onMounted(() => {
    loadTable();
})

const setPage = (page) => {
    loadTable(page)
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Tasa de cambio
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="max-w-xl mx-auto p-4">
                        <!-- Title -->
                        <h2 class="text-xl font-semibold mb-4 text-center">Ingresa un rango de fechas</h2>

                        <!-- Grid with 2 columns -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Fecha inicial</label>
                                <input type="date" v-model="init_date" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Fecha final</label>
                                <input type="date" v-model="end_date" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="text-center">
                            <button @click="sendForm" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                Buscar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm md:rounded-lg" >
                    <div class="max-w-4xl mx-auto p-4">
                        <h2 class="text-xl font-semibold mb-4">Tipo de cambio</h2>
                        <h3 class="text-xl font-semibold mb-4">Estado: {{ loading ? "Cargando": "Data cargada"}}</h3>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-md">
                                <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="py-2 px-4 border-b">No. Peticion</th>
                                    <th class="py-2 px-4 border-b">Fecha</th>
                                    <th class="py-2 px-4 border-b">TC Venta</th>
                                    <th class="py-2 px-4 border-b">TC Compra</th>
                                </tr>
                                </thead>
                                <tbody>

                                <template v-for="(item, idx) in paginatedData" :key="idx">
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b" v-if="item.showId" :rowspan="item.rowspan">{{ item.id }}</td>
                                        <td class="py-2 px-4 border-b">{{item.date}}</td>
                                        <td class="py-2 px-4 border-b">{{item.er_sale}}</td>
                                        <td class="py-2 px-4 border-b">{{item.er_purchase}}</td>
                                    </tr>
                                </template>


                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-end mt-4 space-x-2">
                            <button :disabled="currentPage > 1" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" @click="setPage(currentPage - 1)">Previous</button>
<!--                            <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">1</button>-->
<!--                            <button class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">2</button>-->
                            <button @click="setPage(i)" v-for="i in lastPage" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">{{i}}</button>
                            <button :disabled="currentPage === lastPage" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300" @click="setPage(currentPage + 1)">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
