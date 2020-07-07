<template>
    <div class="container create-article">
        <br>
        <form @submit.prevent="saveArticle" method="POST" enctype="multipart/form-data">
            <div class="loader">
                <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="title">Titlu</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.title }" :disabled="disableForm === true" type="text" minlength="5" maxlength="75" id="title" name="title" placeholder="Titlu" v-model="article.title" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.title }}</p>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="category">Categorie</label>
                    <select class="form-control" v-bind:class="{ 'is-invalid': errors.category }" :disabled="disableForm === true" v-model="article.category" name="category" id="category">
                        <option v-for="(category, index) in availableCategories" :key="index" :value="index">{{ category }}</option>
                    </select>
                    <div class="invalid-feedback">
                        <p>{{ errors.category }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="tags">Tag-uri</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.tags }" v-model="article.tags" type="text" id="tags" name="tags" placeholder="Tag-uri" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.tags }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="slug">Slug</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.slug }" v-model="this.slug" type="text" id="slug" name="slug" placeholder="Slug" disabled="disabled" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.slug }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="content">Continut</label>
                    <br>
                    <div v-show="disableForm !== true">
                        <button type="button" class="btn btn-info" @click="addElement('Title')">Titlu</button>
                        <button type="button" class="btn btn-info" @click="addElement('Paragraph')">Paragraf</button>
                        <button type="button" class="btn btn-info" @click="addElement('Gist')">Gist</button>
                        <button type="button" class="btn btn-info" @click="addElement('Image')">Imagine</button>
                        <button type="button" class="btn btn-info" @click="addElement('Quote')">Citat</button>
                    </div>
                    <textarea class="form-control mt-3" :disabled="disableForm === true" v-bind:class="{ 'is-invalid': errors.content }" minlength="10" name="content" id="content" rows="15" v-model="article.content" placeholder="Continutul articolului..."></textarea>
                    <div class="invalid-feedback">
                        <p>{{ errors.content }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="content">Descriere</label>
                    <textarea class="form-control mt-3" :disabled="disableForm === true" v-bind:class="{ 'is-invalid': errors.description }" minlength="10" name="description" id="description" rows="5" v-model="article.description" placeholder="Descrierea articolului..."></textarea>
                    <div class="invalid-feedback">
                        <p>{{ errors.description }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-2">
                    <label for="est_time">Timp</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.est_time }" :disabled="disableForm === true" type="number" id="est_time" name="est_time" placeholder="Timp estimativ" v-model="article.est_time" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.est_time }}</p>
                    </div>
                </div>
                <div class="form-group col-10">
                    <label for="main_image">Imagine principala</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="main_image" :disabled="disableForm === true" class="custom-file-input" id="main_image" @change="processFile($event)">
                            <label class="custom-file-label" for="main_image">{{ this.article.main_image || "Alege imagine"}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <button class="btn btn-primary" type="submit" v-show="disableForm !== true">Adauga Articol</button>
                    <b-button class="btn btn-dark" v-show="disableForm === true" v-b-modal.leaveModal>Vizualizeaza Articol</b-button>
                </div>
            </div>
        </form>
        <div class="form-row" v-show="!hideImagesForm">
            <div class="form-group col-12">
                <multiple-file-uploader
                        headerMessage=""
                        dropAreaPrimaryMessage="Trage imaginile articolului aici"
                        dropAreaSecondaryMessage="sau apasa click pentru a incarca."
                        postURL="http://localhost:8000/article/create/saveArticleImages"
                        successMessagePath="Imaginile au fost incarcate!"
                        errorMessagePath="Imaginilie nu au putut if incarcate!"
                        fileUploadErrorMessage=""
                        uploadButtonMessage="Incarca"
                        fileNameMessage="Nume fisiere"
                        fileSizeMessage="Marimi fisiere"
                        minFilesErrorMessage="Minimul de imagini necesare pentru incarcare"
                        maxFilesErrorMessage="Maximul de imagini necesare pentru incarcare"
                        retryErrorMessage="Verifica tipul fisierelor si incearca din nou"
                        removeFileMessage="Sterge fisierele"
                        totalFileMessage="Numar total de fisiere:"
                        totalUploadSizeMessage="Marime totala upload fisiere:"
                        :postData="{slug: this.slug}"
                        @upload-success="successHandler"
                        @upload-error="failureHandler"
                ></multiple-file-uploader>
            </div>
        </div>
        <div>
            <b-modal
                    id="leaveModal"
                    title="Paraseste Articolul"
                    ok-title="Da"
                    ok-variant="warning"
                    cancel-title="Anuleaza"
                    @ok="leaveArticle">
                <p class="my-4">Esti sigur ca vrei sa parasesti pagina?</p>
                <p class="my-4">Nu uita sa incarci imaginile folosite in continutul articolului.</p>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import slugify from 'slugify';
    import { BounceLoader } from '@saeris/vue-spinners';
    import MultipleFileUploader from './MultipleFileUploader';

    export default {
        name: 'create-article',
        components: { BounceLoader, MultipleFileUploader },
        data() {
            return {
                availableCategories: [],
                article: {
                    title: "",
                    category: "1",
                    est_time: 1,
                    content: "",
                    description: "",
                    main_image: "",
                    tags: "",
                    slug: "",
                },
                errors: {
                    title: "",
                    category: "",
                    est_time: "",
                    content: "",
                    description: "",
                    tags: "",
                    slug: "",
                },
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
                disableForm: true,
                hideImagesForm: true,
            }
        },
        created: function () {
            this.randomString = this.generateRandomString(12);
            this.setShortcuts();
            this.getCategories();
        },
        computed: {
            /**
             * Compute the slug input.
             */
            slug: function () {
                return (slugify(this.article.title) + "-" + this.randomString).toLowerCase().trim();
            },
            /**
             * Set the Image Button insert content.
             */
            imageShortcut: function() {
                return '\n<img src="../storage/articles/' + this.slug + '/{IMAGE_NAME}" alt="article_image" class="img-fluid mx-auto d-block article-image">';
            },
        },
        methods: {
            /**
             * Get the available categories for the new article.
             */
            getCategories: function () {
                this.loading = true;
                axios.get('/article/create/getCategories')
                    .then(function (response) {
                        if (response.data.success) {
                            this.availableCategories = response.data.response;
                            this.disableForm = false;
                            this.loading = false;
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                        }
                    }.bind(this))
                    .catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                    }.bind(this));
            },
            /**
             * Save the article.
             */
            saveArticle: function () {
                if (!this.validateParameters(this.article)) {
                    return;
                }

                this.loading = true;
                this.disableForm = true;
                let formData = new FormData();

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                formData.append('title', this.article.title.trim());
                formData.append('article_category', this.article.category.trim());
                formData.append('content', this.article.content.trim());
                formData.append('description', this.article.description.trim());
                formData.append('est_time', this.article.est_time);
                formData.append('main_image', this.article.main_image);
                formData.append('tags', this.article.tags);
                formData.append('slug', this.slug);

                axios.post('/article/create/saveArticle', formData, config)
                    .then(function (response) {
                        if (response.data.success) {
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Articolul a fost salvat cu succes.");

                            if (this.article.content.indexOf("<img") !== -1) {
                                this.hideImagesForm = false;
                                this.loading = false;

                                $("html, body").animate({
                                    scrollTop: $(document).height()
                                }, 1000);
                            } else {
                                window.setTimeout(() => {
                                    window.location = this.slug
                                }, 4500);
                                this.toast('b-toaster-bottom-right', "success", "Succes!", "Vei fi redirectionat catre articol in cateva secunde.");
                            }
                        } else {
                            _.forEach(response.data.response, function(error) {
                                this.toast('b-toaster-bottom-right', "danger", "Oops!", error);
                            }.bind(this));

                            this.disableForm = false;
                            this.loading = false;
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la salvarea articolului. Te rog incearca din nou mai tarziu.");

                        this.disableForm = false;
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Create display message using "toast" bootstrap-vue component.
             */
            toast: function (toaster, variant, title, message) {
                this.$bvToast.toast(message, {
                    title: title,
                    variant: variant,
                    toaster: toaster,
                    solid: true,
                    appendToast: true,
                })
            },
            /**
             * Display info message after the images are uploaded.
             */
            successHandler: function (response) {
                this.hideImagesForm = true;

                this.toast('b-toaster-bottom-right', "success", "Succes!", response.info);
                this.toast('b-toaster-bottom-right', "success", "Succes!", "Vei fi redirectionat catre articol in cateva secunde.");

                window.setTimeout(() => {
                    window.location = this.slug;
                }, 4500);
            },
            /**
             * Display error message in case the multi-upload fails.
             */
            failureHandler: function (error) {
                _.forEach(error, function(error) {
                    this.toast('b-toaster-bottom-right', "danger", "Oops!", error);
                }.bind(this));
            },
            /**
             * Add element shortcut.
             */
            addElement: function (element) {
                switch (element) {
                    case 'Title':
                        this.article.content += this.titleShortcut;
                        break;
                    case 'Paragraph':
                        this.article.content += this.paragraphShortcut;
                        break;
                    case 'Gist':
                        this.article.content += this.gistShortcut;
                        break;
                    case 'Image':
                        this.article.content += this.imageShortcut;
                        break;
                    case 'Quote':
                        this.article.content += this.quoteShortcut;
                        break;
                }
            },
            /**
             * Redirect to the article page.
             */
            leaveArticle: function () {
                window.location = this.slug;
            },
            /**
             * Generate a random string
             */
            generateRandomString: function (length) {
                let result           = '';
                let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let charactersLength = characters.length;

                for (let i = 0; i < length; i++) {
                    result += characters.charAt(Math.floor(Math.random() * charactersLength));
                }

                return result;
            },
            /**
             * Process the uploaded file.
             */
            processFile: function (event) {
                //TODO: Imagine 1.65 : 1 FORMAT
                let file = event.target.files[0];

                if (this.isFileImage(file)) {
                    this.article.main_image = file;
                } else {
                    this.toast('b-toaster-bottom-right', "danger", "Oops!", "Imaginea principala trebuia sa aiba una dintre extensiile: 'png', 'jpg', 'jpeg', 'svg'.");
                }
            },
            /**
             * Check if the uploaded file is an image.
             */
            isFileImage: function (file) {
                const acceptedImageTypes = ['image/jpeg', 'image/png'];

                return file && acceptedImageTypes.includes(file['type']);
            },
            /**
             * Set the shortcuts for the elements of the article content.
             */
            setShortcuts: function () {
                this.titleShortcut = '\n<h1>{TEXT}</h1>';
                this.paragraphShortcut = '\n<p>{TEXT}</p>';
                this.gistShortcut = '\n<div id="gist-{NUMBER}" data-src="https://gist.github.com/CoderyRo/{GIST_ID}?file={GIST_FILE}"></div>';
                this.quoteShortcut = '\n<div class="blockquote">\n<p><q>\n{QUOTE}\n</q></p>\n<footer>{AUTHOR}</footer>\n</div>';
            },
            /**
             * Validate the parameters before submiting the form.
             */
            validateParameters: function (parameters) {
                parameters.est_time = parseInt(parameters.est_time);
                let insertContent = ["{TEXT}", "{IMAGE_NAME}", "{NUMBER}", "{GIST_ID}", "{GIST_FILE}", "{QUOTE}", "{AUTHOR}"];

                if (parameters.title.trim().length < 5 || parameters.title.trim().length > 75 || parameters.title.trim() === "") {
                    this.errors.title = "Titlul trebuie sa contina intre 5 si 75 de caractere.";
                    return false;
                }
                this.errors.title = "";

                if (!parameters.category.trim().length) {
                    this.errors.category = "Selecteaza o categorie din lista.";
                    return false;
                }
                this.errors.category = "";

                if (parameters.content.trim().length <= 10 || parameters.content.trim() === "") {
                    this.errors.content = "Continutul trebuie sa contina cel putin 10 caractere.";
                    return false;
                }
                this.errors.content = "";

                let fail = false;
                _.forEach(insertContent, function(value) {
                    if (this.article.content.indexOf(value) !== -1) {
                        this.errors.content = "Completeaza atributele elementelor din continut.";
                        fail = true;
                    }
                }.bind(this));
                if (fail) {
                    return false;
                }
                this.errors.content = "";

                if (parameters.est_time <= 0 || (typeof parameters.est_time) !== 'number') {
                    this.errors.est_time = "Timpul estimativ este invalid.";
                    return false;
                }
                this.errors.est_time = "";

                if (this.slug.trim().length < 18 || this.slug.trim().length > 88 || this.slug.trim() === "") {
                    this.errors.slug = "Slug-ul este gol. Alege un titlu pentru articol.";
                    return false;
                }
                this.errors.slug = "";

                let tagsArray = parameters.tags.split(",");
                if (parameters.tags.trim() === "" || tagsArray.length === 0) {
                    this.errors.tags = "Tag-urile sunt goale. Completeaza cateva tag-uri pentru articol.";
                    return false;
                }
                this.errors.tags = "";

                return true;
            }
        }
    }
</script>

<style></style>
