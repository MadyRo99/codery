<template>
    <div id="create-article">
        <form class="form-control" @submit.prevent="addArticle">
            <input type="text" name="title" placeholder="Titlu" v-model="article.title" required="required">
            <br><br>
            <select v-model="article.category" name="category">
                <option v-for="(category, index) in availableCategories" :key="index" :value="index">{{ category }}</option>
            </select>
            <br><br>
            <input type="number" name="est_time" placeholder="Timp estimativ" v-model="article.est_time" required="required">
            <br><br>
            <textarea name="content" id="content" cols="60" rows="10" v-model="article.content"></textarea>
            <br><br>
            <button class="btn btn-primary">Adauga Articol</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'create-article',
        data() {
            return {
                availableCategories: [],
                article: {
                    title: "",
                    category: 1,
                    est_time: 1,
                    content: "",
                },
            }
        },
        created: function () {
            this.getCategories();
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
                let parameters = {
                    title: this.article.title,
                    article_category: this.article.category,
                    content: this.article.content,
                    est_time: this.article.est_time,
                };

                this.validateParameters(parameters);

                axios.post('/article/create/saveArticle', parameters)
                    .then(function (response) {
                        if (response.data.success) {
                            console.log("good");
                        } else {
                            console.log(response.data.response);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            /**
             * Validate the parameters before submiting the form.
             */
            validateParameters: function (parameters) {
                if (parameters.title.length < 5) {
                    return false;
                }
                if (parameters.content.length <= 10) {
                    return false;
                }
                if (parameters.est_time <= 0 || (typeof parameters.est_time) !== 'number') {
                    return false;
                }c
            }
        }
    }
</script>

<style>

</style>