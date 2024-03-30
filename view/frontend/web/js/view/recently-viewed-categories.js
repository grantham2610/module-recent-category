define([
    'jquery',
    'uiComponent',
    'ko',
    'mage/url'
], function($, Component, ko, urlBuilder) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Grantham_RecentCategory/recently-viewed-categories'
        },

        initialize: function() {
            this._super();
            this.recentlyViewedCategories = ko.observableArray([]);
            this.displayRecentlyViewedCategories();
        },
        displayRecentlyViewedCategories: function() {
            var self = this;
            var viewedCategories = localStorage.getItem('viewed_categories');
            if (!viewedCategories) {
                return; // No categories to fetch
            }
            var categoryIds = JSON.parse(viewedCategories);

            var query = `
                query CategoryList($categoryIds: [String]) {
                    categoryList(filters: { ids: {in: $categoryIds} }) {
                        name
                        description
                        children_count
                        children {
                            uid
                            level
                            name
                            image
                            description
                            path
                            url_path
                            url_key
                            children {
                                uid
                                level
                                name
                                image
                                path
                                url_path
                                url_key
                            }
                        }
                    }
                }
            `;
            $.ajax({
                url: urlBuilder.build('graphql'), // Update with your GraphQL endpoint
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ query: query, variables: { categoryIds: categoryIds } }),
                success: function(response) {
                    var categories = response.data.categoryList;

                    // Update Knockout observable array with the retrieved data
                    self.recentlyViewedCategories(categories);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                }
            });
            /*var viewedCategories = localStorage.getItem('viewed_categories');
            if (!viewedCategories) {
                viewedCategories = [];
            } else {
                viewedCategories = JSON.parse(viewedCategories);
            }
            if (viewedCategories) {
              var CategoriesArray = JSON.parse(viewedCategories);
               // this.singlecategory(CategoriesArray);
                this.recentlyViewedCategories(CategoriesArray);
            }*/
        }
    });
});
