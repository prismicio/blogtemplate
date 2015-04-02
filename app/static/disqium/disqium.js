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
                create: function(identifier) {
                    var thread = {
                        api_key: disqus.apiKey,
                        forum: disqus.forum,
                        identifier: identifier
                    };
                    var url = endpoint + '/threads/create.json?' + $.param(thread);
                    return $.ajax({ url: url, type: 'POST' });
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
        var threadIdentifier = $form.closest('[data-disqium-thread-id]').data('disqium-thread-id');
        DisqusAPI.threads.create(threadIdentifier);
        //DisqusAPI.posts.create('anonymous', 'anonymous@prismic.io', text, '3648676811');
    });

    $scope.find('[data-disqium-thread-id]').hover(function() {
        var $button = $(this).find('.disqium-toggle-thread');
        if(!$button.is('.locked')) {
            $button.toggle('fade-in');
        }
    });
}
