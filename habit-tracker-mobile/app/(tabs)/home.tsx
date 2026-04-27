import { useFocusEffect } from "expo-router";
import { useCallback, useState } from "react";
import { ActivityIndicator, FlatList, View } from "react-native";
import { getPublicGoals, type PublicGoal } from "../../src/api/goals";
import { useAuth } from "../../src/auth/AuthContext";

import { AppBackground } from "../../src/ui/AppBackground";
import { AppCard } from "../../src/ui/AppCard";
import { AppHeader } from "../../src/ui/AppHeader";
import { AppText } from "../../src/ui/AppText";

export default function HomeScreen() {
  const { user, totalPoints, level } = useAuth();
  

  const [items, setItems] = useState<PublicGoal[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  async function load() {
    setError("");
    setLoading(true);
    try {
      const res = await getPublicGoals();

      const list = Array.isArray(res as any)
        ? (res as any)
        : (res as any).goals ?? (res as any).data;

      setItems(list ?? []);
    } catch (e: any) {
      setError(String(e?.message ?? e));
    } finally {
      setLoading(false);
    }
  }

  useFocusEffect(
  useCallback(() => {
    load();
  }, [])
);

  return (
    <AppBackground>
     <AppHeader
  title="Tablica"
  subtitle={`Witaj, ${user?.name ?? user?.email ?? ""}`}
  points={totalPoints}
  level={level}
  rightActions={[
    { title: "Odśwież", onPress: load },
  ]}
/>

      {loading ? (
        <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
          <ActivityIndicator />
          <AppText variant="small" style={{ marginTop: 10 }}>
            Ładuję tablicę…
          </AppText>
        </View>
      ) : (
        <>
          {!!error && (
            <AppText style={{ marginTop: 12, color: "crimson" }}>
              Błąd: {error}
            </AppText>
          )}

          <FlatList
            style={{ marginTop: 12 }}
            data={items}
            keyExtractor={(g) => String(g.id)}
            ItemSeparatorComponent={() => <View style={{ height: 12 }} />}
            renderItem={({ item }) => (
              <AppCard>
                <AppText variant="body" style={{ fontSize: 16, fontWeight: "800" }}>
                  {item.title}
                </AppText>

                <AppText variant="small" style={{ marginTop: 6 }}>
                  Autor:{" "}
                  {item.user?.name ??
                    item.user?.email ??
                    `User #${item.user?.id ?? "?"}`}
                </AppText>

                {!!item.description && (
                  <AppText style={{ marginTop: 8 }}>{item.description}</AppText>
                )}

                <AppText variant="small" style={{ marginTop: 8 }}>
                  Postęp: {item.progress_percent ?? 0}%
                </AppText>

                {!!item.start_date && !!item.end_date && (
                  <AppText variant="small" style={{ marginTop: 6 }}>
                    {String(item.start_date).slice(0, 10)} →{" "}
                    {String(item.end_date).slice(0, 10)}
                  </AppText>
                )}
              </AppCard>
            )}
            ListEmptyComponent={
              <AppText variant="small" style={{ marginTop: 20, opacity: 0.7 }}>
                Brak publicznych celów.
              </AppText>
            }
          />
        </>
      )}
    </AppBackground>
  );
}