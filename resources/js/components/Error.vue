<template >
    <div>

        <section class="content" >
            <div id="block_error">
                <div>
                    <h2>“404 | Not Found“</h2>
                    <p><b>The page you are looking for can't be found!</b></p>
                </div>
            </div>
        </section>
    </div>


</template>
<style>
    .clear{
        clear: both;
        overflow: hidden;
    }
    #block_error{
        width: 845px;
        height: 384px;
        border: 1px solid #cccccc;
        margin: 72px auto 0;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        background: #fff url(http://www.ebpaidrev.com/systemerror/block.gif) no-repeat 0 51px;
    }
    #block_error div{
        padding: 100px 40px 0 186px;
    }
    #block_error div h2{
        color: #218bdc;
        font-size: 24px;
        display: block;
        padding: 0 0 14px 0;
        border-bottom: 1px solid #cccccc;
        margin-bottom: 12px;
        font-weight: normal;
    }
</style>
<script>
    import main from "./script/main";
    export default {
        data()
        {

        },

        mounted() {
            let self=this;

        },
        methods: {


        },
    }
</script>
