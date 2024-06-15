from flask import Flask, jsonify, request
import mysql.connector

app = Flask(__name__)
app.config["JSON_AS_ASCII"] = False

cnx = mysql.connector.connect(
    user="root", password="", host="localhost", database="Webtech"
)

assert cnx.is_connected()


@app.route("/db/rest/user/<username>", methods=["GET"])
def db_rest_user_get(username):
    with cnx.cursor(buffered=True) as cursor:
        query = "SELECT id, username, password_hash FROM Users WHERE username = %s"
        result = cursor.execute(query, (username,))

        if cursor.rowcount == 0:
            return jsonify({"success": True, "found": False, "username": username})
        if cursor.rowcount != 1:
            return (
                jsonify({"success": False, "error": "User found multiple times!"}),
                500,
            )

        (user_id, result, password_hash) = cursor.fetchone()

        return jsonify(
            {
                "success": True,
                "found": True,
                "id": user_id,
                "username": result,
                "password_hash": password_hash,
            }
        )


@app.route("/db/rest/user", methods=["POST"])
def db_rest_user_post():
    request_data = request.get_json()
    username = request_data["username"]
    password_hash = request_data["password_hash"]

    with cnx.cursor() as cursor:
        query = "INSERT INTO Users (username, password_hash) VALUES (%s, %s)"
        cursor.execute(query, (username, password_hash))
        return jsonify({"success": True, "message": "Account saved successfully."})


def get_filter_string(value):
    if value is None:
        return "%"
    return "%" + value + "%"


@app.route("/db/rest/watchlist/<uid>", methods=["GET"])
def db_rest_watchlist_get(uid):
    user_id = int(uid)
    arg_list = request.args
    if len(arg_list) == 0:
        with cnx.cursor(buffered=True) as cursor:
            query = "SELECT series_id, title, seasons, genre, platform FROM Watching WHERE user_id = %s"
            cursor.execute(query, (user_id,))
            output_list = []
            for series_id, title, seasons, genre, platform in cursor.fetchall():
                output_list.append(
                    {
                        "id": series_id,
                        "title": title,
                        "seasons": seasons,
                        "genre": genre,
                        "platform": platform,
                    }
                )
            return jsonify(
                {"success": True, "user_id": user_id, "watched": output_list}
            )
    else:
        filter_title = get_filter_string(arg_list.get("title"))
        filter_genre = get_filter_string(arg_list.get("genre"))
        filter_platform = get_filter_string(arg_list.get("platform"))
        with cnx.cursor(buffered=True) as cursor:
            query = "SELECT series_id, title, seasons, genre, platform FROM Watching WHERE user_id = %s AND title LIKE %s AND genre LIKE %s AND platform LIKE %s"
            cursor.execute(
                query, (user_id, filter_title, filter_genre, filter_platform)
            )

            output_list = []
            for series_id, title, seasons, genre, platform in cursor.fetchall():
                output_list.append(
                    {
                        "id": series_id,
                        "title": title,
                        "seasons": seasons,
                        "genre": genre,
                        "platform": platform,
                    }
                )
            return jsonify(
                {"success": True, "user_id": user_id, "watched": output_list}
            )


@app.route("/db/rest/watchlist/<uid>", methods=["POST"])
def db_rest_watchlist_post(uid):
    request_data = request.get_json()

    title = request_data["title"]
    seasons = request_data["seasons"]
    genre = request_data["genre"]
    platform = request_data["platform"]

    with cnx.cursor() as cursor:
        query = "INSERT INTO Watching (user_id, title, seasons, genre, platform) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(
            query, (int(uid), str(title), int(seasons), str(genre), str(platform))
        )
        cnx.commit()
    return jsonify({"success": True, "message": "Added Successfully"})


@app.route("/db/rest/watchlist/<series_id>", methods=["SET"])
def db_rest_watchlist_set(series_id):
    request_data = dict(request.get_json())

    query = "UPDATE Watching SET "
    param_list = []

    new_user_id = request_data.get("user_id")
    new_title = request_data.get("title")
    new_seasons = request_data.get("seasons")
    new_genre = request_data.get("genre")
    new_platform = request_data.get("platform")

    was_set = False

    if new_user_id is not None:
        if not was_set:
            was_set = True
        else:
            query += ", "
        query += "user_id = %s"
        param_list.append(int(new_user_id))

    if new_title is not None:
        if not was_set:
            was_set = True
        else:
            query += ", "
        query += "title = %s"
        param_list.append(str(new_title))

    if new_seasons is not None:
        if not was_set:
            was_set = True
        else:
            query += ", "
        query += "seasons = %s"
        param_list.append(int(new_seasons))

    if new_genre is not None:
        if not was_set:
            was_set = True
        else:
            query += ", "
        query += "genre = %s"
        param_list.append(str(new_genre))

    if new_platform is not None:
        if not was_set:
            was_set = True
        else:
            query += ", "
        query += "platform = %s"
        param_list.append(str(new_platform))

    assert was_set

    query += " WHERE series_id = %s"
    param_list.append(int(series_id))

    with cnx.cursor() as cursor:
        cursor.execute(query, param_list)
        cnx.commit()
    return jsonify({"success": True, "message": "Updated Successfully"})


@app.route("/db/rest/watchlist/<series_id>", methods=["DELETE"])
def db_rest_watchlist_delete(series_id):
    with cnx.cursor() as cursor:
        query = "DELETE FROM Watching WHERE series_id = %s"
        cursor.execute(query, (int(series_id),))
        cnx.commit()
    return jsonify({"success": True, "message": "Deleted Successfully"})


if __name__ == "__main__":
    try:
        app.run(debug=True, port=5000)
    finally:
        cnx.close()
