#include <bits/stdc++.h>
using namespace std;
vector<bool> global(200001);
bool flag;
int main() {
    ios_base::sync_with_stdio(0);
    int n, curr;
    int count = 0;
    cin >> n;

    for (int i = 0; i < n; i++) {
        cin >> curr;
            if (global[curr-1]) {
                global[curr] = true;
            }
            else
            {
                global[curr] = true;
                ++count;
            }
    }

    cout << count;

    return 0;
}