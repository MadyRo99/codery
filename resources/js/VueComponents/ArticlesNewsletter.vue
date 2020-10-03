<template>
    <div class="articles-newsletters">
        <div class="container">
            <h2>Trimite Newsletter</h2>
            <div class="alert alert-info" role="alert">
                Articolele selectate în această secțiune vor fi trimise prin mail tuturor abonaților la Newsletter.
            </div>
            <br>
            <form @submit.prevent="sendNewsletter" method="POST">
                <div class="loader">
                    <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="title">Titlu</label>
                        <input class="form-control" v-bind:class="{ 'is-invalid': errors.title }" :disabled="disableForm === true" v-model="title" type="text" id="title" name="title" placeholder="Titlu" required="required">
                        <div class="invalid-feedback">
                            <p>{{ errors.title }}</p>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="message">Mesaj</label>
                        <textarea class="form-control mt-3" :disabled="disableForm === true" v-bind:class="{ 'is-invalid': errors.message }" name="message" id="message" rows="15" v-model="message" placeholder="Mesaj..."></textarea>
                        <div class="invalid-feedback">
                            <p>{{ errors.message }}</p>
                        </div>
                    </div>
                </div>
                <div class="form-row" v-if="articles.length">
                    <div class="form-group col-12">
                        <label for="newsletterArticles">Articole</label>
                        <select class="form-control" v-bind:class="{ 'is-invalid': errors.newsletterArticles }" :disabled="disableForm === true" v-model="newsletterArticles" name="newsletterArticles" id="newsletterArticles" multiple>
                            <option value="0">Nu include articole în Newsletter</option>
                            <option v-for="(article, index) in articles" :key="index" :value="String(article.id)">{{ article.title }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12">
                        <button class="btn btn-primary" type="submit" v-show="disableForm !== true">Trimite Newsletter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';

    export default {
        name: 'articles-newsletter',
        components: { BounceLoader },
        data() {
            return {
                title: "",
                message: "",
                newsletterArticles: [],
                articles: {},
                errors: {
                    title: "",
                    message: "",
                    newsletterArticles: "",
                },
                loading: true,
                disableForm: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
            };
        },
        created: function () {
            this.fetchArticles();
        },
        methods: {
            /**
             * Fetch the Articles Basic Info.
             */
            fetchArticles: function () {
                this.loading = true;
                axios.get("/getArticlesBasicInfo")
                    .then(function (response) {
                        this.articles = response.data.response;
                    }.bind(this))
                    .catch(function (error) {
                        console.log(error);
                    }.bind(this)).then(function () {
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Send the Newsletter to the Subscribers.
             */
            sendNewsletter: function () {
                let parameters = {
                    title: this.title,
                    message: this.message,
                    newsletterArticles: this.newsletterArticles
                };

                if (!this.validateParameters(parameters)) {
                    return;
                }

                this.loading = true;
                this.disableForm = false;

                this.toast('b-toaster-bottom-right', "warning", "Te rog așteaptă...", "Newsletter-ul se trimite către abonați.");

                axios.post('/sendNewsletter', parameters)
                    .then(function (response) {
                        if (response.data.success) {
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Newsletter-ul a fost trimis cu succes.");
                            this.disableForm = false;
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", response.data.response);
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare că a apărut o problemă la trimiterea Newsletter-ului. Te rog încearcă din nou mai târziu.");
                    }.bind(this)).then(function () {
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Validate the parameters before submitting the form.
             */
            validateParameters: function (parameters) {
                if (parameters.title.trim().length < 2 || parameters.title.trim() === "") {
                    this.errors.title = "Titlul trebuie să conțină cel puțin 2 caractere.";
                    return false;
                }
                this.errors.title = "";

                if (parameters.message.trim().length < 10 || parameters.message.trim() === "") {
                    this.errors.message = "Conținutul trebuie să conțină cel puțin 10 caractere.";
                    return false;
                }
                this.errors.message = "";

                return true;
            }
        },
    }
</script>

<style>

</style>
