<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { createToaster } from '@meforma/vue-toaster';
const toaster = createToaster({ position: "top-right" });
import { ref } from 'vue';
let page = usePage();


const Header = [
    { text: 'Name', value: 'name' },
    { text: 'email', value: 'email' },
    { text: 'mobile', value: 'mobile' },
    { text: 'Action', value: 'action' }
];
const Item = ref(page.props.customers);
const searchField = ref('name');
const searchValue = ref('');


const DeleteClick = (id) => {
    let text = "Do you went to deleted Customer";
    if (confirm(text) == true) {
        router.get(`/Delete-customer/${id}`);
        toaster.success("Customer Deleted Successfully");
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
                            <h3>Customer</h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <a href="/CustomerSavePage?id=0" class="btn btn-success mx-3 btn-sm">
                                Add Customer
                            </a>
                        </div>

                        <!-- Modal -->
                        <div>
                            <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text"
                                v-model="searchValue">
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item"
                                :rows-per-page="10" :search-field="searchField" :search-value="searchValue" show-index>
                                <template #item-action="{ id, name }">
                                    <Link class="btn btn-success mx-3 btn-sm" :href="`/CustomerSavePage?id=${id}`">Edit
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