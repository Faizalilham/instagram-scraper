# Import necessary libraries
import instaloader
from os.path import expanduser
import pandas as pd
import datetime
from flask import Flask, jsonify, request, render_template
from io import StringIO
from flask_cors import CORS
from textblob import TextBlob


# Create an instance of Instaloader
loader = instaloader.Instaloader()

app = Flask(__name__)
CORS(app, resources={r"/get-instagram-profile?username=faizalfalakh": {"origins": "*"}}) 

# Set the template folder path to your desired location
app = Flask(__name__, template_folder=expanduser("/home/faizal/Instagram-Scraper/"))

@app.route('/')
def index():
    # Render the HTML template for the main page
    return render_template('index.php')




@app.route('/get-instagram-profile', methods=['GET'])
def get_instagram_profile():
    try:
        # Get the profile username from the query parameters
        username = request.args.get('username')

        # Retrieve the public profile
        profile = instaloader.Profile.from_username(loader.context, username)

        follower_count = profile.followers
        

        # Initialize a list to store post data as dictionaries
        profile_data = []

        # Iterate over the profile's posts
        for post in profile.get_posts():
            print(follower_count)
            post_data = {
                "date": post.date_local.strftime('%Y-%m-%d'),
                "caption": post.caption,
                "likes": post.likes,
                "comments": post.comments,
                "engagement": int(post.comments) + int(post.likes),
                "followers": follower_count,
                "link": f'<a href="https://www.instagram.com/p/{post.shortcode}/">Link Postingan</a>',
                "shortcode" : f'https://www.instagram.com/p/{post.shortcode}/'
            }
             # Analisis sentimen dengan TextBlob
        caption = post.caption
        if caption:
            analysis = TextBlob(caption)
            sentiment = analysis.sentiment
            # Dapatkan nilai sentimen (range dari -1 hingga 1)
            sentiment_value = sentiment.polarity
            # Beri label sentimen berdasarkan nilai sentimen
            if sentiment_value > 0:
                post_data["sentimen"] = "Positif"
            elif sentiment_value < 0:
                post_data["sentimen"] = "Negatif"
            else:
                post_data["sentimen"] = "Netral"

        print(post_data)
        profile_data.append(post_data)


        # Create a response with CORS headers
        response = jsonify(profile_data)
        response.headers.add('Access-Control-Allow-Origin', '*')

        return response, 200
        

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
