#include <bits/stdc++.h>

using namespace std;

int main() {
    bool flag;
    ifstream cin("1.txt");
    map<string, vector<bool>> freeTime;
    map<string, vector<string>> whichRoom;
    vector<string> kitten;

    vector<int> temp(24, 0);
    int city, room, query;
    string cityName, currTime, roomName;
    cin >> city;
    for (int i = 0; i < city; ++i) {
        cin >> cityName >> room;
        freeTime[cityName].assign(24, false);
        whichRoom[cityName].assign(24, "0");
        for (int j = 0; j < room; ++j) {
            cin >> currTime >> roomName;
            for (int k = 0; k < 24; ++k) {
                if (currTime[k] == '.') {
                    freeTime[cityName][k] = true;
                    whichRoom[cityName][k] = roomName;
                }

            }
        }
    }
    cin >> query;
    for (int i = 0; i < query; i++) {
        temp.clear();
        temp.assign(24, 0);
        kitten.clear();
        flag = true;
        cin >> room;
        for (int j = 0; j < room; j++) {
            cin >> cityName;
            kitten.push_back(cityName);
            for (int k = 0; k < 24; k++) {
                if (freeTime[cityName][k])
                    temp[k]++;
            }
        }
        for (int j = 0; j < 24; j++) {
            if (temp[j] >= room) {
                cout << "Yes ";
                for (auto q: kitten) {
                    cout << whichRoom[q][j] << " ";

                }
                cout << endl;
                flag = false;
                break;
            }
        }
        if (flag)
            cout << "No" << endl;

    }

    return 0;
}