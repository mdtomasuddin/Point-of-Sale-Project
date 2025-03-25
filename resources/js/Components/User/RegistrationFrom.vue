<script setup>

</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10 center-screen">
                <div class="card animated fadeIn w-100 p-3">
                    <form>
                        <div class="card-body">
                            <h4>Sign Up</h4>
                            <hr />
                            <div class="container-fluid m-0 p-0">
                                <div class="row m-0 p-0">

                                    <div class="col-md-4 p-2">
                                        <label>Name</label>
                                        <input id="name" placeholder="First Name" class="form-control" type="text" />
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Email Address</label>
                                        <input id="email" placeholder="User Email" class="form-control" type="email" />
                                    </div>

                                    <div class="col-md-4 p-2">
                                        <label>Mobile Number</label>
                                        <input id="mobile" placeholder="Mobile" class="form-control" type="mobile" />
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <label>Password</label>
                                        <input id="password" placeholder="User Password" class="form-control"
                                            type="password" />
                                    </div>
                                    <div class="col-md-4 p-2">
                                        <label>Confirm Password</label>
                                        <input id="cpassword" placeholder="User Confirm Password" class="form-control"
                                            type="password" />
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 p-2">
                                        <button type="submit" href="loginForm.html"
                                            class="btn mt-3 w-100  btn-success">Complete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>