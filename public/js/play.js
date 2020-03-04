$(function () {
    const es = new EventSource('http://localhost:3000/.well-known/mercure?topic=lv-social-wall');
    var displayedTweets = [];
    es.onmessage = e => {
        console.log(e);
        var tweetContainer = $('#tweet-feed');
        var data = JSON.parse(e.data);
        var tweetTemplate = createTweetCard(data);
        tweetContainer.prepend(tweetTemplate);
        var tweet = $('#tweet'+data.lastTweetId);
        displayedTweets.push(tweet);
        animateTweet(tweet);
        if (displayedTweets.length > 5) {
            var tweetToRemove = displayedTweets[0];
            tweetToRemove.hide();
            displayedTweets.shift();
        }
        animateGoody(data.goody, data.img);
    };

    function createTweetCard(data)
    {
        var template = "<div id=\"tweet" + data.lastTweetId + "\" class=\"tweet-card\"><img src=\"" + data.img + "\" alt=\"avatar\"><h4>"+ data.user +"</h4><p>"+ data.tweetContent +"</p></div>";
        return template;
    }

    function animateTweet(tweet)
    {
        tweet.fadeOut(600).fadeIn(600).fadeOut(500).fadeIn(500).fadeOut(400).fadeIn(400).fadeOut(300).fadeIn(300);
    }

    function animateGoody(goody, img)
    {
        var imgContainer = $('#img-container-' + goody);
        var userAvatar = "<img  src=\"" + img + "\" style=\"max-width: 150px; max-height: 150px; border-radius: 200px;\">";
        var originalImg = imgContainer.find('img');

        imgContainer.find('img').replaceWith(userAvatar);
        setTimeout(function () {
            imgContainer.find('img').replaceWith(originalImg);
        }, 30000);
    }
});