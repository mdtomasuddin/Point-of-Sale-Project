<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { createToaster } from '@meforma/vue-toaster';
import { ref } from 'vue';
const toaster = createToaster({ position: "top-right" });

const urlParms = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParms.get('id')));
const form = useForm({ name: "", id: id });
let page = usePage();

let URL = '/create-brand';
let brand = page.props.brands;
if (id.value !== 0 && brand !== null) {
    URL = '/brand-update';
    form.name = brand.name;
}
function submit() {
    if (form.name.length == 0) {
        toaster.error("Brand Name is Required");
    } else {
        form.post(URL, {
            onSuccess: () => {
                if (page.props.flash.status === true) {
                    router.get('/Brand-page');
                    toaster.success(page.props.flash.message);
                } else {
                    toaster.error(page.props.flash.message);
                }
            }
        });
    }
}


</script>

<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="/Brand-page" class="btn btn-success mx-3 btn-sm">
                                Back
                            </a>
                        </div>
                        <form @submit.prevent="submit">
                            <div class="card-body">
                                <h4>Save Brand</h4>
                                <br />
                                <input id="name" v-model="form.name" name="name" placeholder="Brand Name"
                                    class="form-control" type="text" />
                                <br />
                                <button type="submit" class="btn w-100 btn-success">Save Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>