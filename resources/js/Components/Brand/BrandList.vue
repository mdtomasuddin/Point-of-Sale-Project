<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { createToaster } from '@meforma/vue-toaster';
import { ref } from 'vue';
const toaster = createToaster({ position: "top-right" });
let page = usePage();

const Header = [
    { text: 'Name', value: 'name' },
    { text: 'Action', value: 'number' }
];
const Item = ref(page.props.brands);
const searchField = ref('name');
const searchValue = ref('');

const DeleteClick = (id) => {
    let text = "Do you went to deleted";
    if (confirm(text) == true) {
        router.get(`/delete-brand/${id}`);
        toaster.success("Brands Deleted Successfully");
    } else {
        text = "You pressed Cancel!";
    }
}
</script>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12"></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Brand </h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <Link href="/BrandSavePage?id=0" class="btn btn-success mx-3 btn-sm">
                            Add Brand
                            </Link>
                        </div>

                        <!-- Modal -->

                        <div>
                            <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text"
                                v-model="searchValue">
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item"
                                :rows-per-page="10" :search-field="searchField" :search-value="searchValue" show-index>
                                <template #item-number="{ id, name }">
                                    <Link class="btn btn-success mx-3 btn-sm" :href="`/BrandSavePage?id=${id}`">Edit
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
</template>

<style scoped></style>