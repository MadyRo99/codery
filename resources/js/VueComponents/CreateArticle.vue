<template>
    <div class="container create-article">
        <br>
        <form v-show="!hideArticleForm" @submit.prevent="addArticle" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="title">Titlu</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.title }" type="text" id="title" name="title" placeholder="Titlu" v-model="article.title" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.title }}</p>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="category">Categorie</label>
                    <select class="form-control" v-bind:class="{ 'is-invalid': errors.category }" v-model="article.category" name="category" id="category">
                        <option v-for="(category, index) in availableCategories" :key="index" :value="index">{{ category }}</option>
                    </select>
                    <div class="invalid-feedback">
                        <p>{{ errors.category }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="slug">Slug</label>
                    <input class="form-control" type="text" id="slug" name="slug" placeholder="Slug" disabled="disabled" v-model="this.slug" required="required">
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
                    <textarea class="form-control mt-3" v-bind:class="{ 'is-invalid': errors.content }" name="content" id="content" rows="15" v-model="article.content" placeholder="Continutul articolului..."></textarea>
                    <div class="invalid-feedback">
                        <p>{{ errors.content }}</p>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-4">
                    <label for="est_time">Timp estimativ</label>
                    <input class="form-control" v-bind:class="{ 'is-invalid': errors.est_time }" type="number" id="est_time" name="est_time" placeholder="Timp estimativ" v-model="article.est_time" required="required">
                    <div class="invalid-feedback">
                        <p>{{ errors.est_time }}</p>
                    </div>
                </div>
                <div class="form-group col-8">
                    <label for="main_image">Imagine principala</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="main_image" class="custom-file-input" id="main_image" @change="processFile($event)">
                            <label class="custom-file-label" for="main_image">{{ this.article.main_image.name || "Alege imagine"}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <button class="btn btn-primary" type="submit">Adauga Articol</button>
                </div>
            </div>
        </form>
        <div class="form-row" v-show="!hideImagesForm">
            <div class="form-group col-12">
                <multiple-file-uploader
                        headerMessage=""
                        dropAreaPrimaryMessage="Trage mai multe poze aici"
                        dropAreaSecondaryMessage="sau apasa click pentru a incarca."
                        postURL="http://localhost:8000/article/create/saveArticleImages"
                        successMessagePath="Imaginile au fost incarcate!"
                        errorMessagePath="Imaginilie nu au putut if incarcate!"
                        fileUploadErrorMessage=""
                        uploadButtonMessage="Incarca"
                        cancelButtonMessage="Anuleaza"
                        fileNameMessage="Nume fisiere"
                        fileSizeMessage="Marimi fisiere"
                        minFilesErrorMessage="Minimul de imagini necesare pentru incarcare"
                        maxFilesErrorMessage="Maximul de imagini necesare pentru incarcare"
                        retryErrorMessage="Verifica tipul fisierelor si incearca din nou"
                        removeFileMessage="Sterge fisierele"
                        totalFileMessage="Numar total de fisiere:"
                        totalUploadSizeMessage="Marime totala upload fisiere:"
                        :postData="this.postSubmitData"
                        @upload-success="successHandler"
                        @upload-error="failureHandler"
                ></multiple-file-uploader>
            </div>
        </div>
    </div>
</template>

<script>
    import MultipleFileUploader from '@updivision/vue2-multi-uploader';
    import slugify from 'slugify';

    export default {
        name: 'create-article',
        components: { MultipleFileUploader },
        data() {
            return {
                availableCategories: [],
                article: {
                    title: "",
                    category: "1",
                    est_time: 1,
                    content: "",
                    main_image: "",
                    slug: "",
                },
                errors: {
                    title: "",
                    category: "",
                    content: "",
                    est_time: "",
                },
                hideArticleForm: false,
                hideImagesForm: true,
                postSubmitData: {},
            }
        },
        created: function () {
            this.randomString = this.generateRandomString(12);
            this.setShortcuts();
            this.getCategories();
        },
        computed: {
            slug: function () {
                return slugify(this.article.title) + "-" + this.randomString;
            },
            imageShortcut: function() {
                return '\n<img src="../storage/articles/' + this.slug + '/{IMAGE_NAME}" alt="article_image" class="img-fluid mx-auto d-block">';
            },
        },
        methods: {
            /**
             * Get the available categories for the new article.
             */
            getCategories: function () {
                axios.get('/article/create/getCategories')
                    .then((response) => {
                        if (response.data.success) {
                            this.availableCategories = response.data.response;
                        } else {
                            console.log(response.data.response);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            /**
             * Add the article.
             */
            addArticle: function () {
                if (!this.validateParameters(this.article)) {
                    return;
                }

                let formData = new FormData();

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                formData.append('title', this.article.title);
                formData.append('article_category', this.article.category);
                formData.append('content', this.article.content);
                formData.append('est_time', this.article.est_time);
                formData.append('main_image', this.article.main_image);
                formData.append('slug', this.slug);

                axios.post('/article/create/saveArticle', formData, config)
                    .then(function (response) {
                        if (response.data.success) {
                            this.hideArticleForm = true;
                            this.hideImagesForm = false;
                            this.postSubmitData.slug = response.data.response.slug;
                        } else {
                            console.log(response.data.response);
                        }
                    }.bind(this))
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            successHandler: function (response) {
                // window.location = response.data.response.slug;
            },
            failureHandler: function (error) {
                console.log(error);
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
             * Generate a random string.
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
                let file = event.target.files[0];

                if (this.isFileImage(file)) {
                    this.article.main_image = file;
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
                this.titleShortcut = '\n<h1></h1>';
                this.paragraphShortcut = '\n<p></p>';
                this.gistShortcut = '\n<div id="gist-" data-src="https://gist.github.com/CoderyRo/{GIST_ID}?file={GIST_FILE}"></div>';
                this.quoteShortcut = '\n<div class="blockquote">\n<p><q>\n{QUOTE}\n</q></p>\n<footer>{AUTHOR}</footer>\n</div>';
            },
            /**
             * Validate the parameters before submiting the form.
             */
            validateParameters: function (parameters) {
                parameters.est_time = parseInt(parameters.est_time);

                if (parameters.title.length < 5) {
                    this.errors.title = "Titlul trebuie sa contina cel putin 5 caractere.";
                    return false;
                }
                this.errors.title = "";
                if (!parameters.category.length) {
                    this.errors.category = "Selecteaza o categorie din lista.";
                    return false;
                }
                this.errors.category = "";
                if (parameters.content.length <= 10) {
                    this.errors.content = "Continutul trebuie sa contina cel putin 10 caractere.";
                    return false;
                }
                this.errors.content = "";
                if (parameters.est_time <= 0 || (typeof parameters.est_time) !== 'number') {
                    this.errors.est_time = "Timpul estimativ este invalid.";
                    return false;
                }
                this.errors.est_time = "";

                return true;
            }
        }
    }
</script>

<style>
.dropAreaDragging{
    background-color:#ccc;
}

.errorMsg {
    display: none;
}

.successMsg {
    display: none;
}
</style>