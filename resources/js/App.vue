<script>
    import Pusher from "pusher-js"

    export default {
        name: 'App',
        data () {
            return {
                messages: [],
            };
        },
        mounted () {
            Pusher.logToConsole = true;
            const pusher = new Pusher("f5844939109a02a656aa", {
                appId: "1785844",
                secret: "3bf8b9beff8a1053c777",
                cluster: 'en',
                useTLS: false,
            });
            let channel   = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                alert(JSON.stringify(data));
            });
        },
    };
</script>

<template>
    <ul>
        <li v-for="message in messages">{{ message }}</li>
    </ul>
</template>

<style scoped>

</style>
