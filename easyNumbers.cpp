#include <bits/stdc++.h>

using namespace std;

int main() {
    vector<int> resh(1000000, true);
    ofstream fout("numbers.txt");
    for (int i = 2; i < 1000000; ++i) {
        if (resh[i] == true) {
            int meow = i;
            int n = 2;
            while (meow * n < 1000000) {
                resh[meow * n] = false;
                ++n;
            }
        }
    }

    for (int i = 2; i < 1000000; ++i) {
        if (resh[i]) fout << i << endl;
    }

    return 0;
}