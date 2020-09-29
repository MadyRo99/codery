<template>
    <div class="article-newsletter">
        <div class="row justify-content-center">
            <div class="article-newsletter-left-side col-12 col-md-8 d-flex align-items-center">
                <div class="newsletter-content">
                    <h1>
                        Fii la curent cu lumea IT!
                    </h1>
                    <p>
                        Primește o dată pe lună cele mai bune articole și stai la curent cu lumea tehnologiei.
                    </p>
                    <div class="row newsletter-container">
                        <input type="email" name="email" placeholder="Adresă de email" class="col-7" v-model="email">
                        <button class="col-5" @click="sendJoinNewsletterMail()">Abonează-te</button>
                    </div>
                    <p class="info d-none">Mulțumim! Un email pentru verificare a fost trimis la adresa specificată.</p>
                </div>
            </div>
            <div class="article-newsletter-right-side col-4 d-none d-md-block p-0">
                <img src="../../../public/images/newsletter_icon.png" alt="newsletter_icon.png">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'newsletter',
        data() {
            return {
                email: "",
            }
        },
        methods: {
            sendJoinNewsletterMail: function () {
                let joinNewsletterUrl = "/joinNewsletter";
                let params = {
                    email: this.email
                };

                axios.post(joinNewsletterUrl, params)
                    .then(function (response) {
                        if (response.data.success) {
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Un email pentru confirmarea abonării a fost trimis la adresa specificată.");
                        }
                    }.bind(this)).catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>

<style>

</style>
