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
            <div class="col-9"
                 v-if="!loading && handledProxies.length > 0">
                <h2>Результат обработки</h2>
                <Result :proxies="handledProxies" />
            </div>

        </div>
    </div>
</template>
<script>
import Result from './components/Result.vue';
import Echo from 'laravel-echo';
import { chunk } from './helpers.js';

export default {
    components: { Result },
    data () {
        return {
            proxies       : null,
            loading       : false,
            handledProxies: [],
            progress      : 0,
            finished      : false,
            message       : '',
            echo          : null
        };
    },
    mounted () {
        this.echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001' // Подставьте сюда адрес вашего сервера WebSockets
        });

        window.Echo = this.echo;

        this.echo.channel('proxy-check')
            .listen('.proxy-is-checked', (event) => {
                // Обрабатываем результаты проверки
                this.handledProxies.push(event.proxy);
                this.progress = Math.round((this.handledProxies.length / this.totalProxies) * 100);

                if (this.progress >= 100) {
                    this.loading = false;
                    this.finished = true;
                }
            });
    },
    methods: {
        sendProxies () {
            const CHUNK_SIZE = 100;

            const proxies = this.proxies.split('\n');
            let chunks    = chunk(proxies, CHUNK_SIZE);
            this.totalProxies = proxies.length;

            this.loading   = true;
            this.progress  = 0;
            this.finished  = false;

            chunks.forEach(chunk => {
                axios.post('/api/v1/proxy/check', {
                        proxies: chunk,
                    },
                ).then((response) => {
                    // Обработка успешного ответа
                }).catch((error) => {
                    // Обработка ошибки
                });
            });
        },
    },
};
</script>
<style scoped>
</style>
