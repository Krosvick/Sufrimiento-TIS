from http.server import BaseHTTPRequestHandler, HTTPServer
import pandas as pd
from urllib.parse import urlparse, parse_qs
import json
from algorithm_controller import algorithm_controller
import sys
sys.path.append("..")

class SimpleAPI(BaseHTTPRequestHandler):
    """
        Handle GET requests to the API.

        Parses the URL to determine the requested endpoint and query parameters.
        If the endpoint is '/recommendations', calls the 'get_top_n_recommendations'
        function from the 'algorithm_controller' module with the provided user ID,
        number of recommendations, predictions, and movies data. Returns a JSON
        response containing the top recommended movies for the user.
        If the endpoint is not recognized, returns a 404 error.

        Query params are expected to be in the format:
        localhost:8001/recommendations?userId=1&n=10
        where 'userId' is the ID of the user for whom to generate recommendations,
        and 'n' is the number of recommendations to generate.

        Args:
            self (SimpleAPI): The instance of the SimpleAPI class.
        
        Returns:
            None
    """
    def do_GET(self):
        parsed_url = urlparse(self.path)
        query_params = parse_qs(parsed_url.query)
        if parsed_url.path == '/recommendations':
            userId = int(query_params['userId'][0])
            n = int(query_params.get('n', [10])[0])
            top_movies = algorithm_controller.get_user_recommendations(userId, ratings_df, model, n)
            response = {'top_movies': top_movies}
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps(response, cls=NpEncoder).encode('utf-8'))
        else:
            self.send_response(404)
            self.end_headers()

import numpy as np

class NpEncoder(json.JSONEncoder):

    def default(self, obj):
        if isinstance(obj, np.integer):
            return int(obj)
        if isinstance(obj, np.floating):
            return float(obj)
        if isinstance(obj, np.ndarray):
            return obj.tolist()
        return super(NpEncoder, self).default(obj)

if __name__ == '__main__':
    server_address = ('', 8001)
    httpd = HTTPServer(server_address, SimpleAPI)
    ratings_df = pd.read_csv('datasets/ratings_small_cleaned.csv')
    model = algorithm_controller.generate_model(ratings_df)
    print('Starting server...')
    httpd.serve_forever()