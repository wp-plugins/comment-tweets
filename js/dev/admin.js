;(function ($) {
	$(function () {

		$('#add-new-tweet').click(function (evt) {

			var $lastTweet, aLastTweetId, iLastId, $newTweetUrl;

			// Find the most recent input in the Comment Tweet container
			$lastTweet = $('#tweet-url-container').children('input:last');

			// Read the last ID
			aLastTweetId = $lastTweet.attr('id').split('_');
			iLastTweetId = aLastTweetId[aLastTweetId.length - 1];
			iLastTweetId++;

			// Now create a new input element
			$newTweetUrl = $('<input />')
				.attr('id', 'tweet_url_' + iLastTweetId)
				.attr('name', 'tweet_url[]')
				.attr('class', 'tweet_url')
				.attr('placeholder', $lastTweet.attr('placeholder'));

			// And append it to the container
			$('#tweet-url-container').append($newTweetUrl);

		});

	});
}(jQuery));