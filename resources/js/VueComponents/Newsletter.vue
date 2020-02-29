<template>
    <div class="article-newsletter">
        <div class="row justify-content-center">
            <div class="article-newsletter-left-side col-12 col-md-8 d-flex align-items-center">
                <div class="newsletter-content">
                    <h1>
                        Fii la curent cu lumea IT!
                    </h1>
                    <p>
                        Primeste o data pe saptamana cele mai bun articole si stai la curent cu lumea tehnologiei.
                    </p>
                    <form>
                        <div class="row">
                            <input type="email" name="newsletter-email" placeholder="Adresa de email" class="col-8">
                            <input type="submit" name="newsletter-submit" class="col-4" value="Aboneaza-te">
                        </div>
                    </form>
                    <p class="info d-none">Multumim! Un email pentru verificare a fost trimis la adresa specificata.</p>
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