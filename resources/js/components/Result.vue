<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>IP:port</td>
            <td>Location</td>
            <td>Proxy type</td>
            <td>Available</td>
            <td>Timeout</td>
            <td>real IP</td>
        </tr>
        </thead>
        <tbody>
            <tr v-for="proxy in proxies" :key="proxy.ip">
                <td>{{ proxy.ip + proxy.port }}</td>
                <td>{{ proxy.country + '/' + proxy.city }}</td>
                <td>{{ proxy.type }}</td>
                <td>{{ proxy.available }}</td>
                <td>{{ proxy.ttl }}</td>
                <td>not data</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: 'Result',
    props: {
        proxies: Array
    },
};
</script>

<style scoped>

</style>
