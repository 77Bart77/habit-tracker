import { useFocusEffect, useRouter } from "expo-router";
import { useCallback, useMemo, useState } from "react";
import { ActivityIndicator, FlatList, Pressable, View } from "react-native";
import { getGoals, type Goal } from "../../src/api/goals";
import { useAuth } from "../../src/auth/AuthContext";

import { AppBackground } from "../../src/ui/AppBackground";
import { AppCard } from "../../src/ui/AppCard";
import { AppHeader } from "../../src/ui/AppHeader";
import { AppText } from "../../src/ui/AppText";
function ymd(d?: string) {
  if (!d) return "";
  return String(d).slice(0, 10);
}

function statusLabel(s?: string) {
  if (!s) return "nieznany";
  const v = String(s).toLowerCase();
  if (v.includes("active")) return "aktywny";
  if (v.includes("finish") || v.includes("done") || v.includes("closed")) return "zakończony";
  return s;
}

export default function GoalsScreen() {
  const [items, setItems] = useState<Goal[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string>("");

  const router = useRouter();
  const { user, totalPoints, level } = useAuth();

  const load = useCallback(async () => {
    setError("");
    setLoading(true);

    try {
      const res = await getGoals(["active", "finished"]);
      const goals = Array.isArray(res as any) ? (res as any) : (res as any).goals;
      setItems(goals ?? []);
    } catch (e: any) {
      setError(String(e?.message ?? e));
    } finally {
      setLoading(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      load();
    }, [load])
  );

  const subtitle = useMemo(() => {
    const name = user?.name ?? user?.email ?? "";
    const count = items.length;
    return `${name}${count ? ` • ${count} ${count === 1 ? "cel" : count < 5 ? "cele" : "celów"}` : ""}`;
  }, [user, items.length]);

  return (
    <AppBackground>
      <AppHeader
  title="Twoje cele"
  subtitle={subtitle}
  points={totalPoints}
  level={level}
  rightActions={[
    { title: "Dodaj", onPress: () => router.push("/goal-create") },
    { title: "Odśwież", onPress: load },
  ]}
/>

      {loading ? (
        <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
          <ActivityIndicator />
          <AppText variant="small" style={{ marginTop: 10 }}>
            Ładuję cele…
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
            ItemSeparatorComponent={() => <View style={{ height: 10 }} />}
            renderItem={({ item }) => {
              const desc = (item as any).description ?? (item as any).desc ?? "";
              const start = ymd((item as any).start_date);
              const end = ymd((item as any).end_date);

              return (
                <Pressable onPress={() => router.push(`/goals/${item.id}` as any)}>
                  <AppCard>
                    {/* Tytuł */}
                    <AppText
                      variant="body"
                      style={{ fontSize: 16, fontWeight: "800" }}
                      numberOfLines={2}
                    >
                      {item.title}
                    </AppText>

                    {/* Opis (opcjonalnie) */}
                    {!!desc && (
                      <AppText
                        variant="small"
                        style={{ marginTop: 6, opacity: 0.85 }}
                        numberOfLines={2}
                      >
                        {String(desc)}
                      </AppText>
                    )}

                    {/* “Badges”: status / publiczny / daty */}
                    <View style={{ marginTop: 10, flexDirection: "row", flexWrap: "wrap", gap: 8 }}>
                      <AppText variant="small" style={{ opacity: 0.8 }}>
                        Status: {statusLabel(item.status)}
                      </AppText>

                      <AppText variant="small" style={{ opacity: 0.8 }}>
                        {item.is_public ? "Publiczny" : "Prywatny"}
                      </AppText>

                      {(start || end) && (
                        <AppText variant="small" style={{ opacity: 0.8 }}>
                          {start ? `Start: ${start}` : ""}{start && end ? " • " : ""}{end ? `Koniec: ${end}` : ""}
                        </AppText>
                      )}
                    </View>
                  </AppCard>
                </Pressable>
              );
            }}
            ListEmptyComponent={
              <AppText variant="small" style={{ marginTop: 20, opacity: 0.7 }}>
                Brak celów.
              </AppText>
            }
            contentContainerStyle={{ paddingBottom: 24 }}
          />
        </>
      )}
    </AppBackground>
  );
}