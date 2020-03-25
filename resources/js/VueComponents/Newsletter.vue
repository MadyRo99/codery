<template>
    <div class="article-newsletter">
        <div class="row justify-content-center">
            <div class="article-newsletter-left-side col-12 col-md-8 d-flex align-items-center">
                <div class="newsletter-content">
                    <h1>
                        Fii la curent cu lumea IT!
                    </h1>
                    <p>
                        Primește o dată pe saptămână cele mai bun articole și stai la curent cu lumea tehnologiei.
                    </p>
                    <form>
                        <div class="row">
                            <input type="email" name="newsletter-email" placeholder="Adresa de email" class="col-7">
                            <input type="submit" name="newsletter-submit" class="col-5" value="Aboneaza-te">
                        </div>
                    </form>
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
    }
</script>

<style></style>