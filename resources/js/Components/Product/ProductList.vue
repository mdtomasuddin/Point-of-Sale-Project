<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { createToaster } from '@meforma/vue-toaster';
const toaster = createToaster({ position: "top-right" });
import { ref } from 'vue';
let page = usePage();


const Header = [
    { text: 'Name', value: 'name' },
    { text: 'Price', value: 'price' },
    { text: 'Unit', value: 'unit' },
    { text: 'Image', value: 'image' },
    {text:'category',value:'category.name'},
    { text: 'Action', value: 'action' }
];
const Item = ref(page.props.products);
const searchValue = ref('');


const DeleteClick = (id) => {
    let text = "Do you went to deleted Customer";
    if (confirm(text) == true) {
        router.get(`/Delete-Product/${id}`);
        toaster.success("product Deleted Successfully");
    } else {
        text = "You pressed Cancel!";
    }
}

</script>

<template>
    <!-- start content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Product</h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <Link href="/ProductSavePage?id=0" class="btn btn-success mx-3 btn-sm">
                            Add Product
                            </Link>
                        </div>
                        <div>
                            <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text"
                                v-model="searchValue">
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item"
                                :rows-per-page="10" :search-field="searchField" :search-value="searchValue" show-index>
                                <template #item-image="{ image }" class="pt-2 pb-2">
                                    <img :src="image" :alt="image" alt="" height="auto" width="40px">
                                </template>
                                <template #item-action="{ id }">
                                    <Link class="btn btn-success mx-3 btn-sm" :href="`/ProductSavePage?id=${id}`">Edit
                                    </Link>
                                    <button class="btn btn-danger btn-sm" @click="DeleteClick(id)">Delete</button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end content -->

</template>

<style scoped></style>