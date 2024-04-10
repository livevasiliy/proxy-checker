<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div>
                    <label for="proxies">Укажите IP адрес(а)</label>
                    <textarea name="proxies"
                              id="proxies"
                              cols="30"
                              rows="10"
                              class="form-control"
                              v-model="proxies"
                              placeholder="Укажите IP адрес"
                    ></textarea>
                    <button class="btn btn-secondary"
                            @click="sendProxies">Проверить
                    </button>
                </div>
            </div>
            <div class="col-9" v-if="!loading && handledProxies.length > 0">
                <h2>Результат обработки</h2>
                <Result :proxies="handledProxies" />
            </div>

        </div>
    </div>
</template>
<script>
import { chunk } from './helpers.js';
import Result    from './components/Result.vue';

export default {
    components: { Result },
    data () {
        return {
            proxies       : null,
            loading       : false,
            handledProxies: [],
        };
    },
    mounted () {
        window.Echo.channel('proxy-check')
            .listen('.proxy-is-checked', (channel) => {
                console.log(channel);
            });
    },
    methods: {
        sendProxies () {
            const CHUNK_SIZE = 100;

            const proxies = this.proxies.split('\n');
            let chunks    = chunk(proxies, CHUNK_SIZE);

            this.loading = true;
            axios.post('/api/v1/proxy/check', {
                    proxies: chunks,
                },
            ).then((response) => {
                this.loading        = false;
                this.handledProxies = [...response.data.result];
            }).catch((error) => {
                this.loading = false;
                console.log(error);
            });
        },
        checkWs () {
            axios.get('/test').then(res => console.log(res)).catch(err => console.log(err));
        },
    },
};
</script>
