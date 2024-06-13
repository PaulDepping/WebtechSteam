from flask import Flask, jsonify, request
from datetime import datetime, date
import random

weather_data_storage = {}
app = Flask(__name__)


def generate_fake_weather_data(datum):
    return {
        "Datum": datum,  # datum
        "Temperatur": random.randint(-10, 35),  # Temperatur in °C
        "Niederschlag": random.random(),  # Niederschlag als Wahrscheinlichkeit
        "Wind": random.randint(0, 100),  # Windgeschwindigkeit in km/h
        "Sonnenschein": random.randint(0, 10),  # Sonneneinstrahlung in Stunden
    }


##
## Holt per GET Daten
##
## curl -X GET http://127.0.0.1:5000/weather
##
@app.route("/weatherXXX", methods=["GET"])
def get_weatherXXX():
    data = generate_fake_weather_data(date.today())
    return jsonify(data)


##
## Holt per GET Daten
##
## curl -X GET http://127.0.0.1:5000/weather
##
@app.route("/weather", methods=["GET"])
def get_weather():
    return jsonify(weather_data_storage)


##
## Holt per GET Daten für ein spezielles Datum
##
## curl -X GET http://127.0.0.1:5000/weather/2024-04-04
##
@app.route("/weather/<date>", methods=["GET"])
def get_weather_by_date(date):
    try:
        try:
            # Versuche, das Datum aus dem Pfad zu parsen, um sicherzustellen, dass es gültig ist
            parsed_date = datetime.strptime(date, "%Y-%m-%d")
        except ValueError:
            return (
                jsonify({"error": "Invalid date format. Please use YYYY-MM-DD."}),
                400,
            )

        data = weather_data_storage.get(date, generate_fake_weather_data(parsed_date))
        return jsonify(data)
    except ValueError:
        return jsonify({"error": "Invalid date format. Please use YYYY-MM-DD."}), 400


##
## Speichert per POST die Daten lokal in eine Variable
##
## curl -X POST http://127.0.0.1:5000/weather/2024-04-04 -H "Content-Type: application/json" -d '{"temperature": 99}'
##
@app.route("/weather/<date>", methods=["POST"])
def set_weather_by_date(date):

    # Extrahiere die Temperatur aus dem JSON-Body der Anfrage
    request_data = request.get_json()

    # Speichere die Temperatur im Dictionary
    weather_data_storage[date] = {
        "Datum": date,
        "Temperatur": request_data["temperature"],
        "Niederschlag": request_data["Niederschlag"],
        "Wind": request_data["Wind"],
        "Sonnenschein": request_data["Sonnenschein"],
    }

    return jsonify({"message": "Temperature data saved successfully."}), 200


##
## Löscht eine Temperatur aus dem Speicher für ein spezielles Datum
##
## curl -X DELETE http://127.0.0.1:5000/weather/2024-04-04
##
@app.route("/weather/<date>", methods=["DELETE"])
def delete_weather_by_date(date):

    # Prüfe, ob für das Datum Daten vorhanden sind, und lösche sie, falls ja
    if date in weather_data_storage:
        del weather_data_storage[date]
        return jsonify({"message": "Temperature data deleted successfully."}), 200
    else:
        return (
            jsonify({"error": "No temperature data found for the provided date."}),
            404,
        )


if __name__ == "__main__":
    app.run(debug=True, port=5000)
