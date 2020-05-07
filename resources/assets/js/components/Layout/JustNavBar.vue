<template>
    <div>
        <div id="content" class="app-content box-shadow-z2 bg pjax-container" role="main">
            <nav-bar></nav-bar>
            <div class="app-footer white bg p-a b-t">
                <div class="pull-right text-sm text-muted">Version 1.0.1</div>
                <span class="text-sm text-muted">&copy; Copyright.</span>
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

export default {
    components: {
        NavBar
    }
}
</script>
