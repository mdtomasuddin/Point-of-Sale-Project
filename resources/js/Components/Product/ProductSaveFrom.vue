<script setup>
import { useForm, usePage, router, Link } from '@inertiajs/vue3'
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
import { ref } from "vue";
import ImageUpload from './ImageUpload.vue';

const urlParams = new URLSearchParams(window.location.search)
let id = ref(parseInt(urlParams.get('id')))

const form = useForm({ name: '', price: '', unit: '', category_id: '', brand_id:'',image: null, id: id.value || null })
const page = usePage()

const categories = ref(page.props.categories)
const brands = ref(page.props.brands)

let URL = "/create-Product";
let list = page.props.product

if (id.value !== 0 && list !== null) {
    URL = "/update-product";
    form.name = list['name'];
    form.id = list['id'];
    form.price = list['price'];
    form.unit = list['unit'];
    form.category_id = list['category_id'];
    form.brand_id = list['brand_id'];
    form.image = list['image'];
}


function submit() {
    form.post(URL, {
        onSuccess: () => {
            if (page.props.flash.status === true) {
                toaster.success(page.props.flash.message);
                setTimeout(() => {
                    router.get("/product-page")
                }, 500)
            } else {
                toaster.warning(page.props.flash.message)
            }
        }
    })
}
</script>

<template>
    <!-- start content -->
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <Link href="/product-page" class="btn btn-success mx-3 btn-sm">
                                Back
                            </Link>
                        </div>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="card-body">
                                <h4>Save Product</h4>
                                <br />
                                <input id="name" name="name" v-model="form.name" placeholder="Product Name"
                                    class="form-control" type="text" />
                                <br />
                                <input id="price" name="price" v-model="form.price" placeholder="Product Price"
                                    class="form-control" type="text" />
                                <br />
                                <input id="unit" name="unit" v-model="form.unit" placeholder="Product Unit"
                                    class="form-control" type="text" />
                                <br />
                                <!-- Category Dropdown -->
                                <div>
                                    <label for="category">Select Category:</label>
                                    <select v-model="form.category_id" class="form-control" id="category">
                                        <option value="" disabled>Select a category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}</option>
                                    </select>
                                </div>
                                <!-- Brand Dropdown -->
                                <div>
                                    <label for="brand">Select Brand:</label>
                                    <select v-model="form.brand_id" class="form-control" id="category">
                                        <option value="" disabled>Select a category</option>
                                        <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                            {{ brand.name }}</option>
                                    </select>
                                </div>
                                <br />
                                <div>
                                    <label for="image">Product Image:</label> <br>
                                    <!-- <input type="file" id="image" @change="handleFileUpload" /> -->
                                    <ImageUpload :productImage="form.image" @image="(e) => form.image = e" />
                                </div>
                                <br />
                                <button type="submit" class="btn w-100 btn-success">Save Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end content -->
</template>

<style scoped></style>