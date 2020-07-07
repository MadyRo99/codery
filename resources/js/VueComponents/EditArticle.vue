<template>
    <div class="container edit-article">
        <br>
        <form @submit.prevent="saveArticle" method="POST" enctype="multipart/form-data">
            <div class="loader">
                <bounce-loader class="custom-class" :class="{ highIndex: loading }" :loading="loading" :color="loader.color" :size="loader.size" :margin="loader.margin"></bounce-loader>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="title">Titlu</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.title }" :disabled="disableForm === true" type="text" id="title" name="title" placeholder="Titlu" v-model="article.title" required="required">
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
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.tags }" :disabled="disableForm === true" v-model="article.tags" type="text" id="tags" name="tags" placeholder="Tags" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.tags }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="slug">Slug</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.slug }" :disabled="disableForm === true" v-model="this.slug" type="text" id="slug" name="slug" placeholder="Slug" disabled="disabled" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.slug }}</p>
                    </div>
                </div>
                <div class="form-group col-3">
                    <label for="status">Status</label>
                    <select class="form-control" v-bind:class="{ 'is-invalid': errors.status }" :disabled="disableForm === true" v-model="article.status" name="status" id="status">
                        <option value="0">Inactiv</option>
                        <option value="1">Activ</option>
                    </select>
                    <div class="invalid-feedback">
                        <p>{{ errors.status }}</p>
                    </div>
                </div>
                <div class="form-group col-3">
                    <b-button
                            class="btn btn-danger"
                            style="position: relative; top: 32px; width: 100%;"
                            @click="disableForm = true"
                            v-b-modal.deleteModal
                    >Stergere</b-button>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="content">Continut</label>
                    <br>
                    <div>
                        <button type="button" class="btn btn-info" @click="addElement('Title')">Titlu</button>
                        <button type="button" class="btn btn-info" @click="addElement('Paragraph')">Paragraf</button>
                        <button type="button" class="btn btn-info" @click="addElement('Gist')">Gist</button>
                        <button type="button" class="btn btn-info" @click="addElement('Image')">Imagine</button>
                        <button type="button" class="btn btn-info" @click="addElement('Quote')">Citat</button>
                    </div>
                    <textarea class="form-control mt-3" v-bind:class="{ 'is-invalid': errors.content }" :disabled="disableForm === true" minlength="10" name="content" id="content" rows="15" v-model="article.content" placeholder="Continutul articolului..."></textarea>
                    <div class="invalid-feedback">
                        <p>{{ errors.content }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="content">Descriere</label>
                    <textarea class="form-control mt-3" v-bind:class="{ 'is-invalid': errors.description }" :disabled="disableForm === true" minlength="10" name="description" id="description" rows="5" v-model="article.description" placeholder="Descrierea articolului..."></textarea>
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
                            <label class="custom-file-label" for="main_image">{{ this.article.displayMainImage }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <button class="btn btn-primary" type="submit">Salveaza Articol</button>
                    <b-button class="btn btn-dark" v-b-modal.leaveModal>Vizualizeaza Articol</b-button>
                </div>
            </div>
        </form>
        <div class="form-row">
            <div class="form-group col-12" style="margin-bottom: 0;">
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
        <h3 v-if="article.images.length">Imaginile atribuite articolului:</h3>
        <h3 v-else>Nu exista imagini atribuite articolului.</h3>
        <br>
        <div class="row">
            <div class="col-md-3" v-for="image in article.images">
                <b-card :title=image
                        :img-src='"../../storage/articles/" + slug + "/" + image'
                        img-alt="article_image"
                        img-top
                        tag="article"
                        class="mb-3">
                    <b-button href="#" variant="danger" @click="deleteImage(image)">Sterge</b-button>
                </b-card>
            </div>
        </div>
        <div>
            <b-modal
                     id="deleteModal"
                     title="Stergere Articolul"
                     ok-title="Stergere"
                     ok-variant="danger"
                     cancel-title="Anuleaza"
                     @hide="disableForm = false"
                     @cancel="disableForm = false"
                     @ok="deleteArticle">
                <p class="my-4">Esti sigur ca vrei sa stergi acest articol?</p>
                <p class="my-4">Odata sters, acesta nu va mai putea fi recuperat.</p>
            </b-modal>
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
                <p class="my-4">Toate modificare nesalvate nu vor fi pastrate.</p>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import { BounceLoader } from '@saeris/vue-spinners';
    import MultipleFileUploader from './MultipleFileUploader';

    export default {
        name: 'edit-article',
        components: { BounceLoader, MultipleFileUploader },
        props: {
            slug: {
                type: String,
                required: true,
            },
        },
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
                    images: [],
                    status: "0",
                    tags: "",
                    savedMainImage: null,
                    displayMainImage: "Alege imagine",
                },
                errors: {
                    title: "",
                    category: "",
                    est_time: "",
                    content: "",
                    description: "",
                    tags: "",
                    slug: "",
                    status: "",
                },
                loading: false,
                loader: {
                    color: "#16E8CA",
                    size: 200,
                    margin: 0,
                },
                disableForm: true,
            }
        },
        created: function () {
            this.fetchArticle();
            this.setShortcuts();
        },
        computed: {
            /**
             * Set the Image Button insert content.
             */
            imageShortcut: function() {
                return '\n<img src="../storage/articles/' + this.slug + '/{IMAGE_NAME}" alt="article_image" class="img-fluid mx-auto d-block article-image">';
            },
        },
        methods: {
            /**
             * Fetch the Article Data as well as the available categories.
             */
            fetchArticle: function () {
                this.loading = true;
                let parameters = {
                    slug: this.slug
                };

                axios.post('/article/fetchUpdateArticleData', parameters)
                    .then((response) => {
                        if (response.data.success) {
                            let article = response.data.response.article;

                            console.log(JSON.parse(article.tags).join(", "));

                            this.article.title = article.title;
                            this.article.category = article.article_category;
                            this.article.est_time = article.est_time;
                            this.article.content = article.content;
                            this.article.description = article.description;
                            this.article.main_image = article.main_image;
                            this.article.savedMainImage = article.main_image || null;
                            this.article.displayMainImage = article.main_image || "Alege imagine";
                            this.article.images = article.images;
                            this.article.tags = JSON.parse(article.tags).join(", ");
                            this.article.status = article.status;

                            if (response.data.response.categories) {
                                this.disableForm = false;
                                this.loading = false;
                                this.availableCategories = response.data.response.categories;
                            } else {
                                this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                            }
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "Se pare ca a aparut o problema la incarcarea paginii. Te rog incearca din nou mai tarziu.");
                        }
                    })
                    .catch(function () {
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
                let formData = new FormData();

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                formData.append('title', this.article.title.trim());
                formData.append('article_category', this.article.category);
                formData.append('content', this.article.content.trim());
                formData.append('description', this.article.description.trim());
                formData.append('est_time', this.article.est_time);
                formData.append('main_image', this.article.main_image);
                formData.append('tags', this.article.tags);
                formData.append('slug', this.slug);
                formData.append('status', this.article.status);

                axios.post('/article/edit/' + this.slug + '/updateArticle', formData, config)
                    .then(function (response) {
                        if (response.data.success) {
                            let updatedMainImage = response.data.response.main_image;
                            if (updatedMainImage != this.article.savedMainImage) {
                                this.article.main_image = null;
                                this.article.savedMainImage = updatedMainImage;
                                this.article.images.push(updatedMainImage);
                            }
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Articolul a fost salvat cu succes.");
                        } else {
                            _.forEach(response.data.response, function(error) {
                                this.toast('b-toaster-bottom-right', "danger", "Oops!", error);
                            }.bind(this));
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", error);
                    }.bind(this)).then(function () {
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Delete an image from the article.
             */
            deleteImage: function (imageName) {
                this.loading = true;
                let parameters = {
                    slug     : this.slug,
                    imageName: imageName,
                };

                let url = '/article/edit/' + this.slug + '/deleteImage';
                axios.post(url, parameters)
                    .then(function (response) {
                        if (response.data.success) {
                            _.filter(this.article.images, function(value, index) {
                                if (value == imageName) {
                                    this.article.images.splice(index, 1);
                                }
                            }.bind(this));

                            if (response.data.mainImage) {
                                this.article.savedMainImage = null;
                                this.article.displayMainImage = "Alege imagine";
                                this.article.main_image = "";
                            }

                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Imaginea articolului a fost stearsa cu succes.");
                        } else if (response.data.success === false) {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "A aparut o eroare la stergerea imaginii. Te rog incearca din nou mai tarziu.");
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "Imaginea nu exista. Te rog verifica inca o data acest lucru.");
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "A aparut o eroare la stergerea imaginii. Te rog incearca din nou mai tarziu.");
                    }.bind(this)).finally(function () {
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Delete the article.
             */
            deleteArticle: function () {
                this.loading = true;
                let parameters = {
                    slug     : this.slug,
                };

                let url = '/article/delete/' + this.slug;
                axios.post(url, parameters)
                    .then(function (response) {
                        if (response.data.success) {
                            window.location = "../../../";
                            this.toast('b-toaster-bottom-right', "success", "Succes!", "Articolul a fost sters cu succes. Vei fi redirectionat in cateva secunde.");
                        } else {
                            this.toast('b-toaster-bottom-right', "danger", "Oops!", "A aparut o eroare la stergerea articolului. Te rog incearca din nou mai tarziu.");
                        }
                    }.bind(this)).catch(function (error) {
                        this.toast('b-toaster-bottom-right', "danger", "Oops!", "A aparut o eroare la stergerea articolului. Te rog incearca din nou mai tarziu.");
                    }.bind(this)).finally(function () {
                        this.loading = false;
                    }.bind(this));
            },
            /**
             * Redirect to the article page.
             */
            leaveArticle: function () {
                window.location = "../" + this.slug;
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
                _.forEach(response.response, function(image) {
                    this.article.images.push(image);
                }.bind(this));
                this.toast('b-toaster-bottom-right', "success", "Succes!", response.info);
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
             * Process the uploaded file.
             */
            processFile: function (event) {
                let file = event.target.files[0];

                if (this.isFileImage(file)) {
                    this.article.main_image = file;
                    this.article.displayMainImage = file.name;
                } else {
                    this.toast('b-toaster-bottom-right', "danger", "Oops!", "Imaginea principala trebuia sa aiba una dintre extensiile: 'png', 'jpg', 'jpeg', 'svg.");
                }
            },
            /**
             * Check if the uploaded file is an image.
             */
            isFileImage: function (file) {
                const acceptedImageTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];

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

                if (this.slug.trim().length < 18 || this.slug.trim().length > 88 || this.slug.trim() === "") {
                    this.errors.slug = "Slug-ul este gol. Alege un titlu pentru articol.";
                    return false;
                }
                this.errors.slug = "";

                if (parameters.status == "0" || parameters.status == "1") {
                    this.errors.status = "";
                } else {
                    this.errors.status = "Selecteaza statusul articolului.";
                    return false;
                }

                if (!parameters.category || parameters.category <= 0) {
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

<style>

.card {
    height: 25rem;
}

.card-title {
    font-size: 18px;
}

.card-body a {
    position: absolute;
    bottom: 1rem;
}

.card-img-top {
    max-height: 17rem;
}

</style>
