<template>
    <div>
        <side-bar></side-bar>
        <div id="content" class="app-content box-shadow-z2 bg pjax-container" role="main">
            <nav-bar></nav-bar>
            <div class="app-footer white bg p-a b-t">
                <div class="pull-right text-sm text-muted">&copy; Copyright <strong>Thanthwe</strong> - Built by <strong>Tide</strong> Version 1.0</div>
                <span class="text-sm text-muted"><a href="#" id="logo bottom"><img :src="'./../../../images/logo-small.png'" alt="logo" /></a></span>
            </div>
            <div class="app-body">
                <!-- ############ PAGE START-->
                <div id="body-content">
                    <slot/>
                </div>
                <!-- ############ PAGE END-->
            </div>
        </div>
    </div>

</template>
<script>
import NavBar from "./NavBar";
import SideBar from "./SideBar";

export default {
    components: {
        NavBar,
        SideBar
    }
}
</script>

<style>
    .w-300{
        width: 70px;
    }
</style>
