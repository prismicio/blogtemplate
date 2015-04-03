$(document).ready(function() {
    var disqus = {
        apiKey: 'E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F',
        forum: 'prismic-blogtemplate'
    };
    Disqium('.blog-main.single', disqus);
});

function Disqium(scope, disqus) {
    var $scope = $(scope);

    var DisqusAPI = (function() {
        var endpoint = 'https://disqus.com/api/3.0';
        return {
            threads: {
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

    $scope.find('[data-disqium-thread-id]').each(function(index , el) {
        var $el = $(el);
        var $button = $('<button class="disqium-toggle-thread">X</button>');
        var $form = $('<form name="disqium-new-post">\
                      <label for="message">Post:</label>\
                      <input type="text" name="post" />\
                      <input type="submit" value="Post"/>\
                      </form>');
        var $wrapper = $('<div class="disqium-wrapper"></div>');
        $wrapper.append($button).append($form);
        $el.append($wrapper);
    });

    $scope.find('[data-disqium-thread-id] .disqium-toggle-thread').click(function() {
        var $button = $(this);
        $button.addClass('locked');
        var $form = $button.siblings('[name=disqium-new-post]');
        $form.toggle('fade-in');
    });

    $scope.find('[data-disqium-thread-id] form[name=disqium-new-post]').submit(function(e) {
        var $form = $(this);
        var text = $form.find('input[name=post]').val();
        e.preventDefault();
        var $paragraph = $form.closest('[data-disqium-thread-id]');
        var identifier = $paragraph.data('disqium-thread-id');
        DisqusAPI.threads.details(identifier).then(function(response) {
            return response.response.id;
        }).fail(function() {
            var title = $paragraph.text().substring(0, 100);
            return DisqusAPI.threads.create(title, identifier).then(function(response) {
                return response.id;
            });
        }).then(function(threadId) {
            DisqusAPI.posts.create('anonymous', 'anonymous@prismic.io', text, threadId);
        });
    });

    $scope.find('[data-disqium-thread-id]').hover(function() {
        var $button = $(this).find('.disqium-toggle-thread');
        if(!$button.is('.locked')) {
            $button.toggle('fade-in');
        }
    });
}
