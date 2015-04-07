$(document).ready(function() {
    Disqium('.blog-main.single', {
        apiKey: 'E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F',
        forum: 'prismic-blogtemplate'
    });
});

function Disqium(scope, disqus) {
    var $scope = $(scope);

    var DisqusAPI = (function() {
        var endpoint = 'https://disqus.com/api/3.0';
        return {
            threads: {
                list: function list(identifiers, cursor) {
                    var params = {
                        forum: disqus.forum,
                        api_key: disqus.apiKey,
                        cursor: cursor,
                        limit: 100,
                        thread: identifiers.map(function(identifier) {
                            return 'ident:' + identifier;
                        })
                    };
                    var url = endpoint + '/threads/list.json?' + $.param(params);
                    return $.ajax({ url: url, type: 'GET' }).then(function(response) {
                        var threads = response.response;
                        if(response.cursor.hasNext) {
                            return list(identifiers, response.cursor.next).then(function(more) {
                                return threads.concat(more);
                            });
                        } else {
                            return $.when(threads);
                        }
                    });
                },
                create: function(title, identifier) {
                    var thread = {
                        title: title,
                        identifier: identifier
                    };
                    var url = 'http://wroom.dev/starterkit/disqus/threads/create';
                    return $.ajax({ url: url, type: 'POST', data: thread });
                },
                details: function(identifier) {
                    var params = {
                        api_key: disqus.apiKey,
                        forum: disqus.forum,
                        'thread:ident': identifier
                    };
                    var url = endpoint + '/threads/details.json?' + $.param(params);
                    return $.ajax({ url: url, type: 'GET' });
                }
            },
            posts: {
                list: function list(threadIds, cursor) {
                    var params = {
                        forum: disqus.forum,
                        api_key: disqus.apiKey,
                        cursor: cursor,
                        limit: 100,
                        thread: threadIds,
                        order: 'asc'
                    };
                    var url = endpoint + '/posts/list.json?' + $.param(params);
                    return $.ajax({ url: url, type: 'GET' }).then(function(response) {
                        var posts = response.response;
                        if(response.cursor.hasNext) {
                            return list(threadIds, response.cursor.next).then(function(more) {
                                return posts.concat(more);
                            });
                        } else {
                            return $.when(posts);
                        }
                    });
                },
                create: function(authorName, authorEmail, message, threadId) {
                    var post = {
                        api_key: disqus.apiKey,
                        author_name: authorName,
                        author_email: authorEmail,
                        message: message,
                        thread: threadId
                    };
                    var url = endpoint + '/posts/create.json?' + $.param(post);
                    return $.ajax({ url: url, type: 'POST'});
                }
            }
        };
    })();

    var identifiers = $scope.find('[data-disqium-thread-id]').map(function() {
        return $(this).data('disqium-thread-id');
    }).toArray();

    var eventuallyThreads = DisqusAPI.threads.list(identifiers).then(function(threads) {
        var threadIds = threads.map(function(thread) { return thread.id; });
        return DisqusAPI.posts.list(threadIds).then(function(posts) {
            return threads.reduce(function(acc, thread) {
                var identifier = thread.identifiers[0];
                acc[identifier] = {
                    id: thread.id,
                    posts: posts.filter(function(post) {
                        return post.thread == thread.id && post.isApproved && !post.isDeleted;
                    })
                };
                return acc;
            }, {});
        });
    });

    var formatPostDate = function(date) {
        var MONTHS = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];

        var day = date.getDay();
        var month = MONTHS[date.getMonth()];
        var year = date.getFullYear();

        return day + ' ' + month + ' ' + year;
    };

    var renderPost = function(post) {
        var author = post.author;
        return '<li class="disqium-post">\
                  <div class="header">\
                    <span class="disqium-post-author">'+ author.name +'</span>\
                    <span class="disqium-post-createdat">'+ formatPostDate(new Date(post.createdAt)) +'</span>\
                  </div>\
                  <p class="disqium-post-text">'+ post.raw_message +'</p>\
               </li>';
    };

    eventuallyThreads.then(function(threads) {
        $scope.find('[data-disqium-thread-id]').each(function(index , el) {
            var $el = $(el);
            var identifier = $el.data('disqium-thread-id');
            var $button = $('<button class="disqium-toggle-thread">X</button>');
            var $form = $('<form name="disqium-new-post">\
                            <label for="message">Post:</label>\
                            <input type="text" name="post" />\
                            <input type="submit" value="Post"/>\
                           </form>');
            var $posts = (function() {
                var thread = threads[identifier];
                if(thread) {
                    return thread.posts.reduce(function(acc, post) {
                        return acc + renderPost(post);
                    }, '');
                } else {
                    return '';
                }
            })();
            var $discussion = $('<ul class="disqium-discussion">' + $posts + '</<ul>');
            var $wrapper = $('<div class="disqium-wrapper"></div>');
            $wrapper.append($button).append($discussion).append($form);
            $el.append($wrapper);
        });

        $scope.find('[data-disqium-thread-id] .disqium-toggle-thread').click(function() {
            var $button = $(this);
            $button.addClass('locked');
            var $form = $button.siblings('[name=disqium-new-post]');
            $form.closest('.disqium-wrapper').toggleClass('fade-in');
        });

        $scope.find('[data-disqium-thread-id] form[name=disqium-new-post]').submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            var text = $form.find('input[name=post]').val();
            var $paragraph = $form.closest('[data-disqium-thread-id]');
            var identifier = $paragraph.data('disqium-thread-id');
            var author = 'anonymous';
            var createPost = function(threadId, text) {
                return DisqusAPI.posts.create(author, 'anonymous@prismic.io', text, threadId).then(function() {
                    var $discussion = $form.siblings('.disqium-discussion');
                    $discussion.append(renderPost({
                        author: {
                            name: author
                        },
                        raw_message: text,
                        createdAt: formatPostDate(new Date())
                    }));
                });
            };
            DisqusAPI.threads.details(identifier).then(function(response) {
                var threadId = response.response.id;
                return createPost(threadId, text);
            }).fail(function() {
                var title = $paragraph.text().substring(0, 100);
                return DisqusAPI.threads.create(title, identifier).then(function(response) {
                    var threadId = response.id;
                    return createPost(threadId, text);
                });
            });
        });

        $scope.find('[data-disqium-thread-id]').hover(function() {
            var $button = $(this).find('.disqium-toggle-thread');
            if(!$button.is('.locked')) {
                $button.toggleClass('fade-in');
            }
        });
    });
}
