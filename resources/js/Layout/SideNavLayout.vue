<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <nav id="topNav" class="navbar fixed-top bg-success top-navbar">
            <div class="container-fluid">
                <Link id="MenuBar" @click="NavOpenClose" class="icon-nav mx-0 my-1 h5">
                <i class="fa text-white fa-bars"></i>
                </Link>
            </div>
        </nav>
        <div id="sideNav" class="side-nav-open">
            <div class="side-nav-top text-center">
                <img alt="" class="side-nav-logo" src="../Assets/img/logo.svg" />
            </div>

            <Link href="#" class="side-bar-item">
            <span class="side-bar-item-icon"><i class="fa fa-tachometer-alt text-green"></i></span>
            <span class="side-bar-item-caption">Dashboard</span>
            </Link>

            <Link href="#" class="side-bar-item">
            <span class="side-bar-item-icon"><i class="fa fa-th-list text-green"></i></span>
            <span class="side-bar-item-caption">Category</span>
            </Link>


            <Link href="/logout" class="side-bar-item">
            <span class="side-bar-item-icon"><i class="fa fa-sign-out-alt text-green"></i></span>
            <span class="side-bar-item-caption">Logout</span>
            </Link>
        </div>
        <div id="content" class="content">
            <div class="container-fluid">
                <main>
                    <slot>

                    </slot>
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped></style>